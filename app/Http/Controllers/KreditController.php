<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\KKB;
use App\Models\Kredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KreditController extends Controller
{
    private $logActivity;
    private $dashboardContoller;
    private $notificationController;
    private $param;

    function __construct()
    {
        $this->logActivity = new LogActivitesController;
        $this->dashboardContoller = new DashboardController;
        $this->notificationController = new NotificationController;
    }

    public function index()
    {
        $this->param['role'] = $this->dashboardContoller->getRoleName();
        $this->param['title'] = 'KKB';
        $this->param['pageTitle'] = 'KKB';
        $this->param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit'])->orderBy('name', 'DESC')->get();
        $this->param['data'] = Kredit::select(
                'kredits.id',
                'kredits.pengajuan_id',
                'kredits.kode_cabang',
                'kkb.id AS kkb_id',
                'kkb.tgl_ketersediaan_unit',
                'kkb.id_tenor_imbal_jasa',
                \DB::raw("(SELECT COUNT(id) FROM document_categories) AS total_doc_requirement"),
                \DB::raw('COALESCE(COUNT(d.id), 0) AS total_file_uploaded'),
                \DB::raw('CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) AS total_file_confirmed'),
                // \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < COALESCE(COUNT(d.id), 0), 'process', 'done') AS status"),
                \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < (SELECT COUNT(id) FROM document_categories), 'process', 'done') AS status"),
            )
            ->join('kkb', 'kkb.kredit_id', 'kredits.id')
            ->leftJoin('documents AS d', 'd.kredit_id', 'kredits.id')
            ->groupBy([
                'kredits.id',
                'kredits.pengajuan_id',
                'kredits.kode_cabang',
                'kkb.id_tenor_imbal_jasa',
                'kkb.id',
                'kkb.tgl_ketersediaan_unit',
                ])
            ->orderBy('total_file_uploaded')
            ->orderBy('total_file_confirmed')
            ->paginate(5);

        return view('pages.kredit.index', $this->param);
    }

    public function uploadBuktiPembayaran(Request $request)
    {
        $status = '';
        $message = '';

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'bukti_pembayaran_scan' => 'required|mimes:pdf|max:2048',
        ], [
            'required' => ':attribute harus diisi.',
            'mimes' => ':attribute harus berupa pdf',
            'max' => ':attribute maksimal 2 Mb',
        ], [
            'id_kkb' => 'Kredit',
            'bukti_pembayaran_scan' => 'Scan berkas polisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $file = $request->file('bukti_pembayaran_scan');
            $file->storeAs('public/dokumentasi-bukti-pembayaran', $file->hashName());
            $document = new Document();
            $document->kredit_id = $request->id_kkb;
            $document->date = date('Y-m-d');
            $document->file = $file->hashName();
            $document->document_category_id  = 1;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas bukti pembayaran.');

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } catch (\Throwable $th) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $th;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }

    public function setTglKetersedianUnit(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 5;

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'date' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            'id_kkb' => 'Kredit',
            'date' => 'Tanggal ketersediaan unit',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            DB::beginTransaction();

            $kkb = KKB::where('id', $request->id_kkb)->first();
            $kkb->tgl_ketersediaan_unit = date('Y-m-d', strtotime($request->date));
            $kkb->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengatur tanggal ketersediaan unit.');

            // send notification
            $this->notificationController->send($action_id);

            DB::commit();

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan : '.$e->getMessage();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }

    public function setPenyerahanUnit(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 6;

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'tgl_pengiriman' => 'required',
            'upload_penyerahan_unit' => 'required|mimes:png,jpg,jpeg|max:4096',
        ], [
            'required' => ':attribute harus diisi.',
            'mimes' => ':attribute harus berupa png,jpg',
            'max' => ':attribute maksimal 4 Mb',
        ], [
            'id_kkb' => 'Kredit',
            'tgl_pengiriman' => 'Tanggal penyerahan unit',
            'upload_penyerahan_unit' => 'Gambar penyerahan unit',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $kkb = KKB::where('id', $request->id_kkb)->first();
            $file = $request->file('upload_penyerahan_unit');
            $file->storeAs('public/dokumentasi-peyerahan', $file->hashName());
            $document = new Document();
            $document->kredit_id = $kkb->kredit_id;
            $document->date = date('Y-m-d', strtotime($request->tgl_pengiriman));
            // $document->date = Carbon::now();
            $document->file = $file->hashName();
            $document->document_category_id  = 2;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengatur tanggal penyerahan unit.');

            // send notification
            $this->notificationController->send($action_id);

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } catch (\Throwable $th) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $th;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }

    public function uploadPolis(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 8;

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'no_polis' => 'required',
            'polis_scan' => 'required|mimes:pdf|max:2048',
        ], [
            'required' => ':attribute harus diisi.',
            'mimes' => ':attribute harus berupa pdf',
            'max' => ':attribute maksimal 2 Mb',
        ], [
            'id_kkb' => 'Kredit',
            'no_polis' => 'Nomor',
            'polis_scan' => 'Scan berkas',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $stnkFile = Document::where('kredit_id', $request->id_kkb)->where('document_category_id', 1)->first();
            $polisDate = date('Y-m-d', strtotime($stnkFile->date . ' +30 days'));

            $file = $request->file('polis_scan');
            $file->storeAs('public/dokumentasi-police', $file->hashName());
            $document = new Document();
            $document->kredit_id = $request->id_kkb;
            $document->date = $polisDate;
            $document->text = $request->no_polis;
            $document->file = $file->hashName();
            $document->document_category_id  = 2;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas nomor polisi.');

            // send notification
            $this->notificationController->send($action_id);

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } catch (\Throwable $th) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $th;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }


    public function uploadBpkb(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 9;

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'no_bpkb' => 'required',
            'bpkb_scan' => 'required|mimes:pdf|max:2048',
        ], [
            'required' => ':attribute harus diisi.',
            'mimes' => ':attribute harus berupa pdf',
            'max' => ':attribute maksimal 2 Mb',
        ], [
            'id_kkb' => 'Kredit',
            'no_bpkb' => 'Nomor',
            'bpkb_scan' => 'Scan berkas BPKB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $polisFile = Document::where('kredit_id', $request->id_kkb)->where('document_category_id', 2)->first();
            $bpkbDate = date('Y-m-d', strtotime($polisFile->date . ' +3 month'));

            $file = $request->file('bpkb_scan');
            $file->storeAs('public/dokumentasi-bpkb', $file->hashName());
            $document = new Document();
            $document->kredit_id = $request->id_kkb;
            $document->date = $bpkbDate;
            $document->text = $request->no_bpkb;
            $document->file = $file->hashName();
            $document->document_category_id  = 3;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas BPKB.');

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } catch (\Throwable $th) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $th;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }

    public function uploadStnk(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 7;

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'no_stnk' => 'required',
            'stnk_scan' => 'required|mimes:pdf|max:2048',
        ], [
            'required' => ':attribute harus diisi.',
            'mimes' => ':attribute harus berupa pdf',
            'max' => ':attribute maksimal 2 Mb',
        ], [
            'id_kkb' => 'Kredit',
            'no_stnk' => 'Nomer STNK',
            'stnk_scan' => 'Scan berkas STNK',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $stnkFile = Document::where('kredit_id', $request->id_kkb)->where('document_category_id', 1)->first();
            $stnkdate = date('Y-m-d', strtotime($stnkFile->date . ' +30 days'));

            $kkb = KKB::where('id', $request->id_kkb)->first();
            $file = $request->file('stnk_scan');
            $file->storeAs('public/dokumentasi-stnk', $file->hashName());
            $document = new Document();
            $document->kredit_id = $kkb->kredit_id;
            $document->date = $stnkdate;
            $document->file = $file->hashName();
            $document->document_category_id  = 4;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas STNK.');

            // send notification
            $this->notificationController->send($action_id);

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } catch (\Throwable $th) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $th;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }

    public function uploadBerkas(Request $request)
    {
        $status = '';
        $message = '';

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'no_stnk' => 'required_with:stnk_scan',
            'stnk_scan' => 'mimes:pdf|max:2048',
            'no_polis' => 'required_with:polis_scan',
            'polis_scan' => 'mimes:pdf|max:2048',
            'no_bpkb' => 'required_with:bpkb_scan',
            'bpkb_scan' => 'mimes:pdf|max:2048',
        ], [
            'required' => ':attribute harus diisi.',
            'mimes' => ':attribute harus berupa pdf',
            'max' => ':attribute maksimal 2 Mb',
        ], [
            'id_kkb' => 'Kredit',
            'no_stnk' => 'Nomor STNK',
            'stnk_scan' => 'Scan berkas STNK',
            'no_polis' => 'Nomor Polis',
            'polis_scan' => 'Scan berkas Polis',
            'no_bpkb' => 'Nomor BPKB',
            'bpkb_scan' => 'Scan berkas BPKB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            DB::beginTransaction();

            $kkb = KKB::where('id', $request->id_kkb)->first();
            // stnk
            if ($request->file('stnk_scan')) {
                $file = $request->file('stnk_scan');
                $file->storeAs('public/dokumentasi-stnk', $file->hashName());

                $document = new Document();
                $document->kredit_id = $kkb->kredit_id;
                $document->text = $request->no_stnk;
                $document->date = date('Y-m-d');
                $document->file = $file->hashName();
                $document->document_category_id  = 3;
                $document->save();

                // send notification
                $this->notificationController->send(7);
            }

            // polis
            if ($request->file('polis_scan')) {
                $file = $request->file('polis_scan');
                $file->storeAs('public/dokumentasi-polis', $file->hashName());

                $document = new Document();
                $document->kredit_id = $kkb->kredit_id;
                $document->text = $request->no_polis;
                $document->date = date('Y-m-d');
                $document->file = $file->hashName();
                $document->document_category_id  = 4;
                $document->save();

                // send notification
                $this->notificationController->send(8);
            }

            // bpkb
            if ($request->file('bpkb_scan')) {
                $file = $request->file('bpkb_scan');
                $file->storeAs('public/dokumentasi-bpkb', $file->hashName());

                $document = new Document();
                $document->kredit_id = $kkb->kredit_id;
                $document->text = $request->no_bpkb;
                $document->date = date('Y-m-d');
                $document->file = $file->hashName();
                $document->document_category_id  = 5;
                $document->save();

                // send notification
                $this->notificationController->send(9);
            }

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas.');

            DB::commit();
            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } catch (\Throwable $th) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $th;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }

    public function confirmBerkas(Request $request)
    {
        $status = '';
        $message = '';

        try {
            if (Auth::user()->role_id == 2) {
                // Cabang
                // stnk
                if ($request->id_stnk) {
                    $stnk = Document::find($request->id_stnk);
                    $docCategory = DocumentCategory::select('name')->find($stnk->document_category_id);

                    $stnk->is_confirm = 1;
                    $stnk->confirm_at = date('Y-m-d');
                    $stnk->confirm_by = Auth::user()->id;
                    $stnk->save();

                    // send notification
                    $this->notificationController->send(10);
                }

                // polis
                if ($request->id_polis) {
                    $polis = Document::find($request->id_polis);
                    $docCategory = DocumentCategory::select('name')->find($polis->document_category_id);

                    $polis->is_confirm = 1;
                    $polis->confirm_at = date('Y-m-d');
                    $polis->confirm_by = Auth::user()->id;
                    $polis->save();

                    // send notification
                    $this->notificationController->send(11);
                }

                // bpkb
                if ($request->id_bpkb) {
                    $bpkb = Document::find($request->id_bpkb);
                    $docCategory = DocumentCategory::select('name')->find($bpkb->document_category_id);

                    $bpkb->is_confirm = 1;
                    $bpkb->confirm_at = date('Y-m-d');
                    $bpkb->confirm_by = Auth::user()->id;
                    $bpkb->save();

                    // send notification
                    $this->notificationController->send(12);
                }

                $this->logActivity->store('Pengguna ' . $request->name . ' mengkonfirmasi berkas ' . $docCategory->name . '.');

                $status = 'success';
                $message = 'Berhasil mengkonfirmasi berkas';
            } else {
                $status = 'failed';
                $message = 'Hanya cabang yang bisa melakukan konfirmasi';
            }
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } catch (\Throwable $th) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $th;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }


    public function confirmDocumentCabang(Request $request)
    {
        $status = '';
        $message = '';

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'category_id' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            'id' => 'Id',
            'category_id' => 'Kategori id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            if (Auth::user()->role_id == 2) {
                // Cabang
                $document = Document::find($request->id);
                $docCategory = DocumentCategory::select('name')->find($request->category_id);

                $document->is_confirm = 1;
                $document->confirm_at = date('Y-m-d');
                $document->confirm_by = Auth::user()->id;
                $document->save();

                if ($request->category_id == 3)
                    $action_id = 10;
                elseif ($request->category_id == 4)
                    $action_id = 11;
                elseif ($request->category_id == 5)
                    $action_id = 12;

                // send notification
                $this->notificationController->send($action_id);

                $this->logActivity->store('Pengguna ' . $request->name . ' mengkonfirmasi berkas ' . $docCategory->name . '.');

                $status = 'success';
                $message = 'Berhasil mengkonfirmasi berkas';
            } else {
                $status = 'failed';
                $message = 'Hanya cabang yang bisa melakukan konfirmasi';
            }
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } catch (\Throwable $th) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $th;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }

    public function confirmDocumentVendor(Request $request)
    {
        $status = '';
        $message = '';

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'category_id' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            'id' => 'Id',
            'category_id' => 'Kategori id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            if (Auth::user()->role_id == 3) {
                // Vendor
                $document = Document::find($request->id);
                $docCategory = DocumentCategory::select('name')->find($request->category_id);

                $document->is_confirm = 1;
                $document->confirm_at = date('Y-m-d');
                $document->confirm_by = Auth::user()->id;
                $document->save();

                if ($request->category_id == 1) {
                    // send notification
                    $this->notificationController->send(4);
                }

                $this->logActivity->store('Pengguna ' . $request->name . ' mengkonfirmasi berkas ' . $docCategory->name . '.');

                $status = 'success';
                $message = 'Berhasil mengkonfirmasi berkas';
            } else {
                $status = 'failed';
                $message = 'Hanya vendor yang bisa melakukan konfirmasi';
            }
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } catch (\Throwable $th) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $th;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }
}
