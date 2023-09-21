<?php

namespace App\Http\Controllers;

use App\Events\KreditBroadcast;
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
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function index(Request $request)
    {
        /**
         * File path LOS
         *
         * upload/{id_pengajuan}/sppk/{filename}
         * upload/{id_pengajuan}/po/{filename}
         * upload/{id_pengajuan}/pk/{filename}
         */
        try {
            $this->param['role_id'] = \Session::get(config('global.role_id_session'));
            $this->param['staf_analisa_kredit_role'] = 'Staf Analis Kredit';
            $this->param['is_kredit_page'] = request()->is('kredit');
            $page_length = $request->page_length ? $request->page_length : 5;
            $this->param['role'] = $this->dashboardContoller->getRoleName();
            $this->param['title'] = 'KKB';
            $this->param['pageTitle'] = 'KKB';
            $this->param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit', 'Bukti Pembayaran Imbal Jasa'])->orderBy('name', 'DESC')->get();

            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            
            $user_id = $token ? $user['id'] : $user->id;
            if (!$token)
                $user_id = 0; // vendor

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
                ->when($request->tAwal && $request->tAkhir, function ($query) use ($request) {
                    return $query->whereBetween('kkb.tgl_ketersediaan_unit', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))]);
                })
                ->when($request->cabang,function($query,$cbg){
                    return $query->where('kredits.kode_cabang',$cbg);
                })
                ->orderBy('total_file_uploaded')
                ->orderBy('total_file_confirmed');

            if (\Session::get(config('global.role_id_session')) == 2) {
                $data->where('kredits.kode_cabang', \Session::get(config('global.user_token_session')) ? \Session::get(config('global.user_kode_cabang_session')) : Auth::user()->kode_cabang);
            }

            if (is_numeric($page_length))
                $data = $data->paginate($page_length);
            else
                $data = $data->get();

            foreach ($data as $key => $value) {
                // retrieve from api
                $host = env('LOS_API_HOST');
                $apiURL = $host . '/kkb/get-data-pengajuan/' . $value->pengajuan_id.'/'.$user_id;

                $headers = [
                    'token' => env('LOS_API_TOKEN')
                ];

                try {
                    $response = Http::timeout(3)->withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

                    $statusCode = $response->status();
                    $responseBody = json_decode($response->getBody(), true);
                    // input file path
                    if ($responseBody) {
                        if (array_key_exists('sppk', $responseBody))
                            $responseBody['sppk'] = "/upload/$value->pengajuan_id/sppk/" . $responseBody['sppk'];
                        if (array_key_exists('po', $responseBody))
                            $responseBody['po'] = "/upload/$value->pengajuan_id/po/" . $responseBody['po'];
                        if (array_key_exists('pk', $responseBody))
                            $responseBody['pk'] = "/upload/$value->pengajuan_id/pk/" . $responseBody['pk'];
                    }

                    // insert response to object
                    if ($user_id != 0) {
                        if ($responseBody) {
                            if (array_key_exists('message', $responseBody)) {
                                if ($responseBody['message'] == 'Data not found') {
                                    unset($data[$key]);
                                }
                            }
                            if (array_key_exists('id_pengajuan', $responseBody)) {
                                $value->detail = $responseBody;
                            }
                        }
                    }
                    else {
                        $value->detail = $responseBody;
                    }
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // return $e->getMessage();
                }
            }

            $data_array = [];
            if($request->status != null){
                foreach($data as $rows){
                    if($rows->status == $request->status){
                        array_push($data_array,$rows);
                    }
                }
                $this->param['data'] = $this->paginate($data_array);
            }else{
                $this->param['data'] = $data;
            }

            // Search query
            $search_q = strtolower($request->get('query'));
            if ($search_q) {
                foreach ($data as $key => $value) {
                    $exists = 0;
                    if ($value->detail) {
                        if ($value->detail['nama']) {
                            if (str_contains(strtolower($value->detail['nama']), $search_q)) {
                                $exists++;
                            }
                        }
                        if ($value->detail['no_po']) {
                            if (str_contains(strtolower($value->detail['no_po']), $search_q)) {
                                $exists++;
                            }
                        }
                    }
                    if ($exists == 0)
                        unset($data[$key]); // remove data
                }
            }

            foreach ($data as $key => $value) {
                $buktiPembayaran = Document::where('kredit_id', $value->id)
                                            ->where('document_category_id', 1)
                                            ->first();

                $penyerahanUnit = Document::where('kredit_id', $value->id)
                                            ->where('document_category_id', 2)
                                            ->first();

                $stnk = Document::where('kredit_id', $value->id)
                                            ->where('document_category_id', 3)
                                            ->first();

                $polis = Document::where('kredit_id', $value->id)
                                    ->where('document_category_id', 4)
                                    ->first();

                $bpkb = Document::where('kredit_id', $value->id)
                                ->where('document_category_id', 5)
                                ->first();

                $imbalJasa = Document::where('kredit_id', $value->id)
                                    ->where('document_category_id', 6)
                                    ->first();

                $setImbalJasa = DB::table('tenor_imbal_jasas')->find($value->id_tenor_imbal_jasa);

                $value->bukti_pembayaran = $buktiPembayaran;
                $value->penyerahan_unit = $penyerahanUnit;
                $value->stnk = $stnk;
                $value->bpkb = $bpkb;
                $value->polis = $polis;
                $value->imbal_jasa = $imbalJasa;
                $value->set_imbal_jasa = $setImbalJasa;
            }
            $this->param['data'] = $data;

            return view('pages.kredit.index', $this->param);
        } catch (\Exception $e) {
            return $e->getMessage();
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

    public function loadDataJson(Request $request) {
        try {
            $this->param['role_id'] = \Session::get(config('global.role_id_session'));
            $this->param['staf_analisa_kredit_role'] = 'Staf Analis Kredit';
            // $this->param['is_kredit_page'] = request()->is('kredit');
            $this->param['is_kredit_page'] = str_contains(url()->current(), 'kredit');
            $page_length = $request->page_length ? $request->page_length : 5;
            $current_page = $request->page ? $request->page : 1;
            $this->param['role'] = $this->dashboardContoller->getRoleName();
            $this->param['title'] = 'KKB';
            $this->param['pageTitle'] = 'KKB';
            $this->param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit', 'Bukti Pembayaran Imbal Jasa'])->orderBy('name', 'DESC')->get();

            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            
            $user_id = $token ? $user['id'] : $user->id;
            if (!$token)
                $user_id = 0; // vendor

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
                ->when($request->tAwal && $request->tAkhir, function ($query) use ($request) {
                    return $query->whereBetween('kkb.tgl_ketersediaan_unit', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))]);
                })
                ->when($request->cabang,function($query,$cbg){
                    return $query->where('kredits.kode_cabang',$cbg);
                })
                ->orderBy('total_file_uploaded')
                ->orderBy('total_file_confirmed');

            if (\Session::get(config('global.role_id_session')) == 2) {
                $data->where('kredits.kode_cabang', \Session::get(config('global.user_token_session')) ? \Session::get(config('global.user_kode_cabang_session')) : Auth::user()->kode_cabang);
            }

            if (is_numeric($page_length)) {
                // $data = $data->paginate($page_length);
                $data = $data->paginate($page_length, ['*'], 'page', $current_page);
            }
            else
                $data = $data->get();

            foreach ($data as $key => $value) {
                // retrieve from api
                $host = env('LOS_API_HOST');
                $apiURL = $host . '/kkb/get-data-pengajuan/' . $value->pengajuan_id.'/'.$user_id;

                $headers = [
                    'token' => env('LOS_API_TOKEN')
                ];

                try {
                    $response = Http::timeout(3)->withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

                    $statusCode = $response->status();
                    $responseBody = json_decode($response->getBody(), true);
                    // input file path
                    if ($responseBody) {
                        if (array_key_exists('sppk', $responseBody))
                            $responseBody['sppk'] = "/upload/$value->pengajuan_id/sppk/" . $responseBody['sppk'];
                        if (array_key_exists('po', $responseBody))
                            $responseBody['po'] = "/upload/$value->pengajuan_id/po/" . $responseBody['po'];
                        if (array_key_exists('pk', $responseBody))
                            $responseBody['pk'] = "/upload/$value->pengajuan_id/pk/" . $responseBody['pk'];
                    }

                    // insert response to object
                    if ($user_id != 0) {
                        if ($responseBody) {
                            if (array_key_exists('message', $responseBody)) {
                                if ($responseBody['message'] == 'Data not found') {
                                    unset($data[$key]);
                                }
                            }
                            if (array_key_exists('id_pengajuan', $responseBody)) {
                                $value->detail = $responseBody;
                            }
                        }
                    }
                    else {
                        $value->detail = $responseBody;
                    }
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // return $e->getMessage();
                }
            }

            $data_array = [];
            if($request->status != null){
                foreach($data as $rows){
                    if($rows->status == $request->status){
                        array_push($data_array,$rows);
                    }
                }
                $this->param['data'] = $this->paginate($data_array);
            }

            // Search query
            $search_q = strtolower($request->get('query'));
            if ($search_q) {
                foreach ($data as $key => $value) {
                    $exists = 0;
                    if ($value->detail) {
                        if ($value->detail['nama']) {
                            if (str_contains(strtolower($value->detail['nama']), $search_q)) {
                                $exists++;
                            }
                        }
                        if ($value->detail['no_po']) {
                            if (str_contains(strtolower($value->detail['no_po']), $search_q)) {
                                $exists++;
                            }
                        }
                    }
                    if ($exists == 0)
                        unset($data[$key]); // remove data
                }
            }

            foreach ($data as $key => $value) {
                $buktiPembayaran = Document::where('kredit_id', $value->id)
                                            ->where('document_category_id', 1)
                                            ->first();

                $penyerahanUnit = \App\Models\Document::where('kredit_id', $value->id)
                                            ->where('document_category_id', 2)
                                            ->first();

                $stnk = \App\Models\Document::where('kredit_id', $value->id)
                                            ->where('document_category_id', 3)
                                            ->first();

                $polis = Document::where('kredit_id', $value->id)
                                    ->where('document_category_id', 4)
                                    ->first();

                $bpkb = Document::where('kredit_id', $value->id)
                                ->where('document_category_id', 5)
                                ->first();

                $imbalJasa = Document::where('kredit_id', $value->id)
                                    ->where('document_category_id', 6)
                                    ->first();

                $setImbalJasa = DB::table('tenor_imbal_jasas')->find($value->id_tenor_imbal_jasa);

                $value->bukti_pembayaran = $buktiPembayaran;
                $value->penyerahan_unit = $penyerahanUnit;
                $value->stnk = $stnk;
                $value->bpkb = $bpkb;
                $value->polis = $polis;
                $value->imbal_jasa = $imbalJasa;
                $value->set_imbal_jasa = $setImbalJasa;
            }
            
            $this->param['data'] = $data;

            $html = view('pages.kredit.partial._table', $this->param)->render();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully load data',
                'html' => $html,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan. '.$e->getMessage()
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan. '.$e->getMessage()
            ]);
        }
    }

    public function getDataPO($pengajuan_id) {
        try {
            $host = env('LOS_API_HOST');
            $apiURL = $host . '/kkb/get-data-pengajuan-by-id/' . $pengajuan_id;

            $headers = [
                'token' => env('LOS_API_TOKEN')
            ];

            $response = Http::timeout(3)->withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody) {
                if (array_key_exists('no_po', $responseBody) && array_key_exists('nama', $responseBody))
                    return $responseBody;
                else
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Not found',
                    ]);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function getDataCabang($kode_cabang) {
        try {
            $host = env('BIO_INTERFACE_API_HOST');
            $apiURL = $host . '/v1/cabang/' . $kode_cabang;

            $response = Http::timeout(3)->withOptions(['verify' => false])->get($apiURL);

            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody) {
                if(array_key_exists('data', $responseBody)){
                    if ($responseBody['data'])
                        return $responseBody['data'];
                }
                
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Not found',
                ]);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
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
            $kkb = KKB::find($request->id_kkb);
            $kredit = Kredit::find($kkb->kredit_id);
            $document = new Document();
            $document->kredit_id = $kkb->kredit_id;
            $document->date = date('Y-m-d');
            $document->file = $file->hashName();
            $document->document_category_id  = 1;
            $document->save();

            $vendor = User::select('users.email', 'v.name')
                            ->join('vendors AS v', 'v.id', 'users.vendor_id')
                            ->where('users.role_id', 3)
                            ->first();

            // retrieve from api
            $dataPO = $this->getDataPO($kredit->pengajuan_id);

            if ($vendor) {
                // send notif
                $notifTemplate = NotificationTemplate::find(3);
                
                $this->notificationController->sendEmail($vendor->email,  [
                    'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                    'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                    'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                    'to' => $vendor->name,
                    'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                ]);
            }


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
            event(new KreditBroadcast('event created'));

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
            $kredit = Kredit::find($kkb->kredit_id);
            $kkb->tgl_ketersediaan_unit = date('Y-m-d', strtotime($request->date));
            $kkb->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengatur tanggal ketersediaan unit.');

            // retrieve from api
            $dataPO = $this->getDataPO($kredit->pengajuan_id);
            $cabang = $this->getDataCabang($kredit->kode_cabang);
            // send notification
            // $this->notificationController->send($action_id, $kkb->kredit_id);
            $notifTemplate = NotificationTemplate::find(2);
            
            $this->notificationController->sendEmail($cabang['email'],  [
                'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                'to' => 'Cabang '.$dataPO['cabang'],
                'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
            ]);

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
            event(new KreditBroadcast('event created'));

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
            $kredit = Kredit::find($kkb->kredit_id);
            $document = new Document();
            $document->kredit_id = $kkb->kredit_id;
            $document->date = date('Y-m-d', strtotime($request->tgl_pengiriman));
            // $document->date = Carbon::now();
            $document->file = $file->hashName();
            $document->document_category_id  = 2;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengatur tanggal penyerahan unit.');

            // retrieve from api
            $dataPO = $this->getDataPO($kredit->pengajuan_id);
            $cabang = $this->getDataCabang($kredit->kode_cabang);
            // send notification
            // $this->notificationController->send($action_id, $kkb->kredit_id);
            $notifTemplate = NotificationTemplate::find(5);
                
            $this->notificationController->sendEmail($cabang['email'],  [
                'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                'to' => 'Cabang '.$dataPO['cabang'],
                'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
            ]);

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

            event(new KreditBroadcast('event created'));

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
            $kkb = KKB::where('id', $request->id_kkb)->first();
            $kredit = Kredit::find($kkb->kredit_id);
            $document = new Document();
            $document->kredit_id = $request->id_kkb;
            $document->date = $polisDate;
            $document->text = $request->no_polis;
            $document->file = $file->hashName();
            $document->document_category_id  = 2;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas nomor polisi.');

            // send notification
            // $this->notificationController->send($action_id, $request->id_kkb);

            // retrieve from api
            $dataPO = $this->getDataPO($kredit->pengajuan_id);
            $cabang = $this->getDataCabang($kredit->kode_cabang);

            $notifTemplate = NotificationTemplate::find(2);
                
            $this->notificationController->sendEmail($cabang['email'],  [
                'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                'to' => 'Cabang '.$dataPO['cabang'],
                'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
            ]);


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
            $kkb = KKB::where('id', $request->id_kkb)->first();
            $kredit = Kredit::find($kkb->kredit_id);
            $document->kredit_id = $request->id_kkb;
            $document->date = $bpkbDate;
            $document->text = $request->no_bpkb;
            $document->file = $file->hashName();
            $document->document_category_id  = 3;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas BPKB.');

            // send notif
            // $this->notificationController->send($action_id, $request->id_kkb);

            // retrieve from api
            $dataPO = $this->getDataPO($kredit->pengajuan_id);
            $cabang = $this->getDataCabang($kredit->kode_cabang);
            $notifTemplate = NotificationTemplate::find(2);
                
            $this->notificationController->sendEmail($cabang['email'],  [
                'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                'to' => 'Cabang '.$dataPO['cabang'],
                'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
            ]);




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
            $kredit = Kredit::find($kkb->kredit_id);
            $document = new Document();
            $document->kredit_id = $kkb->kredit_id;
            $document->date = $stnkdate;
            $document->file = $file->hashName();
            $document->document_category_id  = 4;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas STNK.');

            // send notification
            // $this->notificationController->send($action_id, $request->id_kkb);
            $dataPO = $this->getDataPO($kredit->pengajuan_id);
            $cabang = $this->getDataCabang($kredit->kode_cabang);
            $notifTemplate = NotificationTemplate::find(2);
                
            $this->notificationController->sendEmail($cabang['email'],  [
                'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                'to' => 'Cabang '.$dataPO['cabang'],
                'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
            ]);


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
                $kredit = Kredit::find($kkb->kredit_id);
                $document->text = $request->no_stnk;
                $document->date = date('Y-m-d');
                $document->file = $file->hashName();
                $document->document_category_id  = 3;
                $document->save();

                $dataPO = $this->getDataPO($kredit->pengajuan_id);
                $cabang = $this->getDataCabang($kredit->kode_cabang);

                $notifTemplate = NotificationTemplate::find(7);
                    
                $this->notificationController->sendEmail($cabang['email'],  [
                    'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                    'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                    'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                    'to' => 'Cabang '.$dataPO['cabang'],
                    'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                ]);
    
            }

            // polis
            if ($request->file('polis_scan')) {
                $file = $request->file('polis_scan');
                $file->storeAs('public/dokumentasi-polis', $file->hashName());

                $document = new Document();
                $document->kredit_id = $kkb->kredit_id;
                $kredit = Kredit::find($kkb->kredit_id);
                $document->text = $request->no_polis;
                $document->date = date('Y-m-d');
                $document->file = $file->hashName();
                $document->document_category_id  = 4;
                $document->save();


                $dataPO = $this->getDataPO($kredit->pengajuan_id);
                $cabang = $this->getDataCabang($kredit->kode_cabang);
                $notifTemplate = NotificationTemplate::find(8);
                    
                $this->notificationController->sendEmail($cabang['email'],  [
                    'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                    'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                    'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                    'to' => 'Cabang '.$dataPO['cabang'],
                    'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                ]);
            }

            // bpkb
            if ($request->file('bpkb_scan')) {
                $file = $request->file('bpkb_scan');
                $file->storeAs('public/dokumentasi-bpkb', $file->hashName());

                $document = new Document();
                $document->kredit_id = $kkb->kredit_id;
                $kredit = Kredit::find($kkb->kredit_id);
                $document->text = $request->no_bpkb;
                $document->date = date('Y-m-d');
                $document->file = $file->hashName();
                $document->document_category_id  = 5;
                $document->save();



                $dataPO = $this->getDataPO($kredit->pengajuan_id);
                $cabang = $this->getDataCabang($kredit->kode_cabang);
                $notifTemplate = NotificationTemplate::find(9);
                    
                $this->notificationController->sendEmail($cabang['email'],  [
                    'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                    'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                    'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                    'to' => 'Cabang '.$dataPO['cabang'],
                    'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                ]);
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

            event(new KreditBroadcast('event created'));

            return response()->json($response);
        }
    }

    public function confirmBerkas(Request $request)
    {
        $status = '';
        $message = '';

        try {
            \DB::beginTransaction();
            if (\Session::get(config('global.role_id_session')) == 2) {
                // Cabang
                $doc_cat_name = 'undifined';
                // stnk
                if ($request->has('id_stnk')) {
                    if (is_numeric($request->id_stnk) && $request->id_stnk != 0) {
                        $stnk = Document::find($request->id_stnk);
                        $docCategory = DocumentCategory::select('name')->find($stnk->document_category_id);
                        $doc_cat_name = $docCategory->name;
                        // $kkb = KKB::where('id', $request->id_kkb)->first();
                        $kredit = Kredit::find($stnk->kredit_id);
                        // send notification
                        if (!$stnk->is_confirm)
                            // $this->notificationController->send(12, $stnk->kredit_id);
                        $vendor = User::select('users.email', 'v.name')
                                        ->join('vendors AS v', 'v.id', 'users.vendor_id')
                                        ->where('users.role_id', 3)
                                        ->first();

                             // retrieve from api
                        $dataPO = $this->getDataPO($kredit->pengajuan_id);

                        if ($vendor) {
                            // send notif
                        $notifTemplate = NotificationTemplate::find(10);

                        $this->notificationController->sendEmail($vendor->email,  [
                                'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                                'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                                'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                                'to' => $vendor->name,
                                'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                            ]);
                        }

                        $stnk->is_confirm = 1;
                        $stnk->confirm_at = date('Y-m-d');
                        $stnk->confirm_by = \Session::get(config('global.user_id_session'));
                        $stnk->save();
                    }
                }

                // polis
                if ($request->has('id_polis')) {
                    if (is_numeric($request->id_polis) && $request->id_polis != 0) {
                        $polis = Document::find($request->id_polis);
                        $docCategory = DocumentCategory::select('name')->find($polis->document_category_id);
                        $doc_cat_name = $docCategory->name;
                        $kredit = Kredit::find($polis->kredit_id);
                        // send notification
                        if (!$polis->is_confirm)
                            // $this->notificationController->send(13, $polis->kredit_id);
                            // $this->notificationController->send(12, $stnk->kredit_id);
                        $vendor = User::select('users.email', 'v.name')
                                            ->join('vendors AS v', 'v.id', 'users.vendor_id')
                                            ->where('users.role_id', 3)
                                            ->first();

                                // retrieve from api
                        $dataPO = $this->getDataPO($kredit->pengajuan_id);

                        if ($vendor) {
                                // send notif
                        $notifTemplate = NotificationTemplate::find(11);

                        $this->notificationController->sendEmail($vendor->email,  [
                                    'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                                    'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                                    'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                                    'to' => $vendor->name,
                                    'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                        ]);
                    }
    
                        $polis->is_confirm = 1;
                        $polis->confirm_at = date('Y-m-d');
                        $polis->confirm_by = \Session::get(config('global.user_id_session'));
                        $polis->save();
                    }
                }

                // bpkb
                if ($request->has('id_bpkb')) {
                    if (is_numeric($request->id_bpkb) && $request->id_bpkb != 0) {
                        $bpkb = Document::find($request->id_bpkb);
                        $docCategory = DocumentCategory::select('name')->find($bpkb->document_category_id);
                        $doc_cat_name = $docCategory->name;
                        $kredit = Kredit::find($bpkb->kredit_id);
                        // send notification
                        if (!$bpkb->is_confirm)
                            // $this->notificationController->send(14, $bpkb->kredit_id);
                            $vendor = User::select('users.email', 'v.name')
                                            ->join('vendors AS v', 'v.id', 'users.vendor_id')
                                            ->where('users.role_id', 3)
                                            ->first();

                         // retrieve from api
                            $dataPO = $this->getDataPO($kredit->pengajuan_id);

                            if ($vendor) {
                                    // send notif
                            $notifTemplate = NotificationTemplate::find(12);

                            $this->notificationController->sendEmail($vendor->email,  [
                                        'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                                        'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                                        'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                                        'to' => $vendor->name,
                                        'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                            ]);
                        }
    
                        $bpkb->is_confirm = 1;
                        $bpkb->confirm_at = date('Y-m-d');
                        $bpkb->confirm_by = \Session::get(config('global.user_id_session'));
                        $bpkb->save();
                    }
                }

                $this->logActivity->store('Pengguna ' . $request->name . ' mengkonfirmasi berkas ' . $doc_cat_name . '.');

                \DB::commit();
                $status = 'success';
                $message = 'Berhasil mengkonfirmasi berkas';
            } else {
                $status = 'failed';
                $message = 'Hanya cabang yang bisa melakukan konfirmasi';
            }
        } catch (\Exception $e) {
            \DB::rollback();
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollback();
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } catch (\Throwable $th) {
            \DB::rollback();
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $th;
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            event(new KreditBroadcast('confirm berkas'));

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
            if (\Session::get(config('global.role_id_session')) == 2) {
                // Cabang
                $document = Document::find($request->id);
                $docCategory = DocumentCategory::select('name')->find($request->category_id);
                $kkb = KKB::where('id', $document->id_kkb)->first();
                $kredit = Kredit::find($kkb->kredit_id);    
                $document->is_confirm = 1;
                $document->confirm_at = date('Y-m-d');
                $document->confirm_by = \Session::get(config('global.user_id_session'));
                $document->save();

                if ($request->category_id == 3)
                    $action_id = 12;
                elseif ($request->category_id == 4)
                    $action_id = 13;
                elseif ($request->category_id == 5)
                    $action_id = 14;

                    $dataPO = $this->getDataPO($kredit->pengajuan_id);
                    $cabang = $this->getDataCabang($kredit->kode_cabang);
                    // send notification
                    // $this->notificationController->send($action_id, $kkb->kredit_id);
                    $notifTemplate = NotificationTemplate::find(2);
                        
                    $this->notificationController->sendEmail($cabang['email'],  [
                        'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                        'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                        'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                        'to' => 'Cabang '.$dataPO['cabang'],
                        'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                    ]);

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
            if (\Session::get(config('global.role_id_session')) == 3) {
                // Vendor
                $document = Document::find($request->id);
                $kredit = Kredit::find($document->kredit_id);
                $docCategory = DocumentCategory::select('name')->find($request->category_id);
                $document->is_confirm = 1;
                $document->confirm_at = date('Y-m-d');
                $document->confirm_by = \Session::get(config('global.user_id_session'));
                $document->save();

                $dataPO = $this->getDataPO($kredit->pengajuan_id);
                $cabang = $this->getDataCabang($kredit->kode_cabang);
                // send notification
                // $this->notificationController->send($action_id, $kkb->kredit_id);
                $notifTemplate = NotificationTemplate::find(4);
                    
                $this->notificationController->sendEmail($cabang['email'],  [
                    'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                    'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                    'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                    'to' => 'Cabang '.$dataPO['cabang'],
                    'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                ]);
    

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

            event(new KreditBroadcast('event created'));

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
            if (\Session::get(config('global.role_id_session')) == 2) {
                // Cabang
                $document = Document::find($request->id);
                $docCategory = DocumentCategory::select('name')->find($request->category_id);
                // $kkb = KKB::where('id', $document->id_kkb)->first();
                $kredit = Kredit::find($document->kredit_id);
                $document->is_confirm = 1;
                $document->confirm_at = date('Y-m-d');
                $document->confirm_by = \Session::get(config('global.user_id_session'));
                $document->save();

                if ($request->category_id == 2) {
                $vendor = User::select('users.email', 'v.name')
                                    ->join('vendors AS v', 'v.id', 'users.vendor_id')
                                    ->where('users.role_id', 3)
                                    ->first();

                    // retrieve from api
                $dataPO = $this->getDataPO($kredit->pengajuan_id);

                if ($vendor) {
                // send notif
                $notifTemplate = NotificationTemplate::find(6);

                $this->notificationController->sendEmail($vendor->email,  [
                    'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                    'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                    'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                    'to' => $vendor->name,
                    'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                ]);
                }
        
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

            event(new KreditBroadcast('event created'));

            return response()->json($response);
        }
    }


    public function show($id)
    {
        $status = '';
        $message = '';
        $data = null;
        try {
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $user_id = $token ? $user['id'] : $user->id;
            $user_nip = $token ? $user['data']['nip'] : $user->nip;
            $user_id = $token ? $user['id'] : $user->id;
                if (!$token)
                    $user_id = 0; // vendor

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
            $apiURL = $host . '/kkb/get-data-pengajuan/' . $kredit->pengajuan_id.'/'.$user_id;

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
                    if (array_key_exists('sppk', $responseBody))
                        $responseBody['sppk'] = "/upload/$kredit->pengajuan_id/sppk/" . $responseBody['sppk'];
                    if (array_key_exists('po', $responseBody))
                        $responseBody['po'] = "/upload/$kredit->pengajuan_id/po/" . $responseBody['po'];
                    if (array_key_exists('pk', $responseBody))
                        $responseBody['pk'] = "/upload/$kredit->pengajuan_id/pk/" . $responseBody['pk'];
                    if (array_key_exists('tanggal', $responseBody))
                        $responseBody['tanggal'] = date('d-m-Y', strtotime($responseBody['tanggal']));
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // return $e->getMessage();
            }
            
            // retrieve karyawan data
            $karyawan = $this->penggunaController->getKaryawan($user_nip);


            if(is_array($karyawan)){
                if (array_key_exists('error', $karyawan))
                    $karyawan = null;
            }else{
                $karyawan = null;
            }

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
            $file = $request->file('file_imbal_jasa');
            $file->storeAs('public/dokumentasi-imbal-jasa', $file->hashName());
            $document = new Document();
            $document->kredit_id = $request->id_kkbimbaljasa;
            $kredit = Kredit::find($document->kredit_id);
            $document->date = Carbon::now();
            $document->file = $file->hashName();
            $document->document_category_id  = 6;
            $document->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas imbal jasa.');

            // send notification
            // $this->notificationController->send($action_id, $request->id_kkbimbaljasa);
            $vendor = User::select('users.email', 'v.name')
                        ->join('vendors AS v', 'v.id', 'users.vendor_id')
                        ->where('users.role_id', 3)
                        ->first();

            // retrieve from api
            $dataPO = $this->getDataPO($kredit->pengajuan_id);

            if ($vendor) {
            // send notif
            $notifTemplate = NotificationTemplate::find(13);

            $this->notificationController->sendEmail($vendor->email,  [
                'title' => $notifTemplate ? $notifTemplate->title : 'Upload Imbal Jasa',
                'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                'to' => $vendor->name,
                'body' => $notifTemplate ? $notifTemplate->content : 'Imbal jasa telah di upload'
                ]);
            }

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

            event(new KreditBroadcast('event created'));

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
            $document->confirm_by = \Session::get(config('global.user_id_session'));
            $document->save();

            // $kkb = KKB::where('id', $request->id_kkb)->first();
            $kredit = Kredit::find($document->kredit_id);

            $this->logActivity->store('Pengguna ' . Auth::user()->name . ' mengkonfirmasi berkas imbal jasa.');

            // send notification
            // $this->notificationController->send($action_id, $document->kredit_id);

            $dataPO = $this->getDataPO($kredit->pengajuan_id);
            $cabang = $this->getDataCabang($kredit->kode_cabang);
            // send notification
            // $this->notificationController->send($action_id, $kkb->kredit_id);
            $notifTemplate = NotificationTemplate::find(14);
                
            $this->notificationController->sendEmail($cabang['email'],  [
                'title' => $notifTemplate ? $notifTemplate->title : 'Konfirmasi Imbal jasa',
                'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                'to' => 'Cabang '.$dataPO['cabang'],
                'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
            ]);


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

            event(new KreditBroadcast('event created'));

            return response()->json($response);
        }
    }
}
