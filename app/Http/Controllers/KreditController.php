<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Master\PenggunaController;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\KKB;
use App\Models\Kredit;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KreditController extends Controller
{
    private $logActivity;
    private $dashboardContoller;
    private $notificationController;
    private $penggunaController;
    private $param;

    function __construct()
    {
        $this->logActivity = new LogActivitesController;
        $this->dashboardContoller = new DashboardController;
        $this->notificationController = new NotificationController;
        $this->penggunaController = new PenggunaController;
    }

    public function index()
    {
        /**
         * File path LOS
         *
         * upload/{id_pengajuan}/sppk/{filename}
         * upload/{id_pengajuan}/po/{filename}
         * upload/{id_pengajuan}/pk/{filename}
         */

        try {
            $this->param['role'] = $this->dashboardContoller->getRoleName();
            $this->param['title'] = 'KKB';
            $this->param['pageTitle'] = 'KKB';
            $this->param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit', 'Bukti Pembayaran Imbal Jasa'])->orderBy('name', 'DESC')->get();

            $data = Kredit::select(
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
                \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < (SELECT COUNT(id) FROM document_categories), 'in progress', 'done') AS status"),
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
                ->orderBy('total_file_confirmed');

            if (Auth::user()->role_id == 2) {
                $data->where('kredits.kode_cabang', Auth::user()->kode_cabang);
            }
            $data = $data->paginate(5);

            foreach ($data as $key => $value) {
                // retrieve from api
                $host = env('LOS_API_HOST');
                $apiURL = $host . '/kkb/get-data-pengajuan/' . $value->pengajuan_id;

                $headers = [
                    'token' => env('LOS_API_TOKEN')
                ];

                try {
                    $response = Http::timeout(3)->withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

                    $statusCode = $response->status();
                    $responseBody = json_decode($response->getBody(), true);
                    // input file path
                    $responseBody['sppk'] = "/upload/$value->pengajuan_id/sppk/" . $responseBody['sppk'];
                    $responseBody['po'] = "/upload/$value->pengajuan_id/po/" . $responseBody['po'];
                    $responseBody['pk'] = "/upload/$value->pengajuan_id/pk/" . $responseBody['pk'];

                    // insert response to object
                    $value->detail = $responseBody;
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // return $e->getMessage();
                }
            }
            $this->param['data'] = $data;

            return view('pages.kredit.index', $this->param);
        } catch (\Exception $e) {
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

    public function uploadBuktiPembayaran(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 4;

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

            // send notif
            $this->notificationController->send($action_id, $request->id_kkb);

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
        $action_id = 6;

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
            $this->notificationController->send($action_id, $kkb->kredit_id);

            DB::commit();

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan : ' . $e->getMessage();
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
        $action_id = 7;

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
            $this->notificationController->send($action_id, $kkb->kredit_id);

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
        $action_id = 10;

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
            $this->notificationController->send($action_id, $request->id_kkb);

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
        $action_id = 11;

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

            // send notif
            $this->notificationController->send($action_id, $request->id_kkb);

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
        $action_id = 9;

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
            $this->notificationController->send($action_id, $request->id_kkb);

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
                $this->notificationController->send(9, $kkb->kredit_id);
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
                $this->notificationController->send(10, $kkb->kredit_id);
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
                $this->notificationController->send(11, $kkb->kredit_id);
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
                if (is_numeric($request->id_stnk)) {
                    $stnk = Document::find($request->id_stnk);
                    $docCategory = DocumentCategory::select('name')->find($stnk->document_category_id);
                    
                    // send notification
                    if (!$stnk->is_confirm)
                        $this->notificationController->send(12, $stnk->kredit_id);

                    $stnk->is_confirm = 1;
                    $stnk->confirm_at = date('Y-m-d');
                    $stnk->confirm_by = Auth::user()->id;
                    $stnk->save();

                }

                // polis
                if (is_numeric($request->id_polis)) {
                    $polis = Document::find($request->id_polis);
                    $docCategory = DocumentCategory::select('name')->find($polis->document_category_id);

                    // send notification
                    if (!$polis->is_confirm)
                        $this->notificationController->send(13, $polis->kredit_id);

                    $polis->is_confirm = 1;
                    $polis->confirm_at = date('Y-m-d');
                    $polis->confirm_by = Auth::user()->id;
                    $polis->save();

                }

                // bpkb
                if (is_numeric($request->id_bpkb)) {
                    $bpkb = Document::find($request->id_bpkb);
                    $docCategory = DocumentCategory::select('name')->find($bpkb->document_category_id);

                    // send notification
                    if (!$bpkb->is_confirm)
                        $this->notificationController->send(14, $bpkb->kredit_id);

                    $bpkb->is_confirm = 1;
                    $bpkb->confirm_at = date('Y-m-d');
                    $bpkb->confirm_by = Auth::user()->id;
                    $bpkb->save();

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
                    $action_id = 12;
                elseif ($request->category_id == 4)
                    $action_id = 13;
                elseif ($request->category_id == 5)
                    $action_id = 14;

                // send notification
                $this->notificationController->send($action_id, $document->kredit_id);

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
                    $this->notificationController->send(5, $document->kredit_id);
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

    public function confirmPenyerahanUnit(Request $request)
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

                if ($request->category_id == 2) {
                    // send notification
                    $this->notificationController->send(8, $document->kredit_id);
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

    public function show($id)
    {
        $status = '';
        $message = '';
        $data = null;
        try {
            $kredit = Kredit::find($id);
            $document = DocumentCategory::select(
                'd.id',
                \DB::raw("DATE_FORMAT(d.date, '%d-%m-%Y') AS date"),
                'd.file',
                'document_categories.name AS category',
                'd.text',
                'd.is_confirm',
                \DB::raw("DATE_FORMAT(d.confirm_at, '%d-%m-%Y') AS confirm_at"),
                'd.confirm_by',
            )
                ->leftJoin('documents AS d', 'd.document_category_id', 'document_categories.id')
                ->where('d.kredit_id', $id)
                ->orWhereNull('d.kredit_id')
                ->get();
            foreach ($document as $key => $value) {
                switch ($value->category) {
                    case "Bukti Pembayaran":
                        $value->file_path = asset('storage') . "/dokumentasi-bukti-pembayaran/" . $value->file;
                        break;
                    case "Penyerahan Unit":
                        $value->file_path = asset('storage') . "/dokumentasi-peyerahan/" . $value->file;
                        break;
                    case "BPKB":
                        $value->file_path = asset('storage') . "/dokumentasi-bpkb/" . $value->file;
                        break;
                    case "Polis":
                        $value->file_path = asset('storage') . "/dokumentasi-polis/" . $value->file;
                        break;
                    case "STNK":
                        $value->file_path = asset('storage') . "/dokumentasi-stnk/" . $value->file;
                        break;
                    default:
                        $value->file_path = 'not found';
                        break;
                }
            }

             // retrieve from api
            $host = config('global.los_api_host');
            $apiURL = $host . '/kkb/get-data-pengajuan/' . $kredit->pengajuan_id;

            $headers = [
                'token' => config('global.los_api_token')
            ];

            $responseBody = null;

            try {
                $response = Http::withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);
                // input file path
                if ($responseBody) {
                    $responseBody['sppk'] = "/upload/$kredit->pengajuan_id/sppk/" . $responseBody['sppk'];
                    $responseBody['po'] = "/upload/$kredit->pengajuan_id/po/" . $responseBody['po'];
                    $responseBody['pk'] = "/upload/$kredit->pengajuan_id/pk/" . $responseBody['pk'];
                    $responseBody['tanggal'] = date('d-m-Y', strtotime($responseBody['tanggal']));
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // return $e->getMessage();
            }

            // retrieve karyawan data
            $karyawan = $this->penggunaController->getKaryawan(Auth::user()->nip);
            
            if (array_key_exists('error', $karyawan))
                $karyawan = null;

            $data = [
                'documents' => $document,
                'pengajuan' => $responseBody,
                'karyawan' => $karyawan,
            ];
            $status = 'success';
            $message = 'Berhasil mengambil data';
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
                'data' => $data,
            ];

            return response()->json($response);
        }
    }

    public function uploadUImbalJasa(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 9;

        $validator = Validator::make($request->all(), [
            'id_kkbimbaljasa' => 'required',
            'file_imbal_jasa' => 'required|max:2048',
        ], [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute maksimal 2 Mb',
        ], [
            'id_kkbimbaljasa' => 'Kredit',
            'file_imbal_jasa' => 'Scan berkas Imbal Jasa',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $kkb = KKB::where('id', $request->id_kkbimbaljasa)->first();
            $file = $request->file('file_imbal_jasa');
            $file->storeAs('public/dokumentasi-imbal-jasa', $file->hashName());
            $document = new Document();
            $document->kredit_id = $kkb->kredit_id;
            $document->date = Carbon::now();
            $document->file = $file->hashName();
            $document->document_category_id  = 6;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas imbal jasa.');

            // send notification
            $this->notificationController->send($action_id, $request->id_kkbimbaljasa);

            $status = 'success';
            $message = 'Berhasil mengupload berkas imbal jasa.';
            // $message = $request->all();
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

    public function confirmUploadUImbalJasa(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 9;

        try {
            $document = Document::find($request->id);
            $document->is_confirm = true;
            $document->confirm_at = Carbon::now();
            $document->confirm_by = Auth::user()->id;
            $document->save();

            $this->logActivity->store('Pengguna ' . Auth::user()->name . ' mengkonfirmasi berkas imbal jasa.');

            // send notification
            $this->notificationController->send($action_id, $document->kredit_id);

            $status = 'success';
            $message = 'Berhasil mengkonfirmasi berkas imbal jasa.';
            // $message = $request->all();
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
