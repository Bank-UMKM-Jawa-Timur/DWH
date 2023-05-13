<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\KKB;
use App\Models\Kredit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KreditController extends Controller
{
    private $logActivity;
    private $dashboardContoller;
    private $param;

    function __construct()
    {
        $this->logActivity = new LogActivitesController;
        $this->dashboardContoller = new DashboardController;
    }

    public function index()
    {
        $this->param['role'] = $this->dashboardContoller->getRoleName();
        $this->param['title'] = 'KKB';
        $this->param['pageTitle'] = 'KKB';
        $this->param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit'])->orderBy('name', 'DESC')->get();
        $this->param['data'] = Kredit::select(
            'kredits.*',
            'kkb.id AS kkb_id',
            'kkb.tgl_ketersediaan_unit',
            'kkb.imbal_jasa',
        )
            ->join('kkb', 'kkb.kredit_id', 'kredits.id')
            ->orderBy('kredits.id')
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
            $kkb = KKB::where('id', $request->id_kkb)->first();
            $kkb->tgl_ketersediaan_unit = date('Y-m-d', strtotime($request->date));
            $kkb->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengatur tanggal ketersediaan unit.');

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan';
        } catch (\Illuminate\Database\QueryException $e) {
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

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'tgl_pengiriman' => 'required',
            'upload_penyerahan_unit' => 'required|mimes:png,jpg|max:4096',
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
            $document->document_category_id  = 1;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengatur tanggal penyerahan unit.');

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

    public function uploadPolice(Request $request)
    {
        $status = '';
        $message = '';

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'no_police' => 'required',
            'police_scan' => 'required|mimes:pdf|max:2048',
        ], [
            'required' => ':attribute harus diisi.',
            'mimes' => ':attribute harus berupa pdf',
            'max' => ':attribute maksimal 2 Mb',
        ], [
            'id_kkb' => 'Kredit',
            'no_police' => 'Nomor',
            'police_scan' => 'Scan berkas polisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $stnkFile = Document::where('kredit_id', $request->id_kkb)->where('document_category_id', 1)->first();
            $policeDate = date('Y-m-d', strtotime($stnkFile->date . ' +30 days'));

            $file = $request->file('police_scan');
            $file->storeAs('public/dokumentasi-police', $file->hashName());
            $document = new Document();
            $document->kredit_id = $request->id_kkb;
            $document->date = $policeDate;
            $document->text = $request->no_police;
            $document->file = $file->hashName();
            $document->document_category_id  = 2;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas nomor polisi.');

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
            $policeFile = Document::where('kredit_id', $request->id_kkb)->where('document_category_id', 2)->first();
            $bpkbDate = date('Y-m-d', strtotime($policeFile->date . ' +3 month'));

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

    public function confirmDocument(Request $request)
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
                // $docCategory = DocumentCategory::select('name')->where('id', $request->category_id);
                $docCategory = DocumentCategory::select('name')->find($request->category_id);

                $document->is_confirm = 1;
                $document->confirm_at = date('Y-m-d');
                $document->confirm_by = Auth::user()->id;
                $document->save();

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
}
