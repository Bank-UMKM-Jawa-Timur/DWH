<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Master\PenggunaController;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kredit;
use App\Models\Notification;
use App\Models\Target;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    private $role_id;
    private $param;


    function __construct()
    {
        $this->role_id = Session::get(config('global.role_id_session'));
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
            $total_target = 0;
            $target = Target::select('id', 'nominal', 'total_unit')->where('is_active', 1)->first();
            $this->param['target'] = $target;

            $data_realisasi = [];
            $total_terealisasi = 0;

            $all_data = Kredit::select(
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
                ->whereNotNull('kredits.pengajuan_id')
                ->whereNull('kredits.imported_data_id')
                ->get();
            foreach ($all_data as $key => $value) {
                if ($value->status == 'done')
                    $total_terealisasi++;
            }
            if ($target)
                $total_target = $target->total_unit;
                
            $this->param['total_belum_terealisasi'] = $total_target - $total_terealisasi;
            $this->param['total_terealisasi'] = $total_terealisasi;

            $notification = Notification::select('notifications.id', 'notifications.read', 'notifications.extra', 'notifications.created_at', 'temp.title', 'temp.content')
                                            ->join('users AS u', 'u.id', 'notifications.user_id')
                                            ->join('notification_templates AS temp', 'temp.id', 'notifications.template_id')
                                            ->where('u.id', \Session::get(config('global.user_id_session')))
                                            ->where('notifications.read', 0)
                                            ->orderBy('notifications.created_at', 'DESC')
                                            ->get();
            $this->param['notification'] = $notification;

            $this->param['role_id'] = \Session::get(config('global.role_id_session'));
            $this->param['staf_analisa_kredit_role'] = 'Staf Analis Kredit';
            $this->param['is_kredit_page'] = request()->is('kredit');
            $page_length = $request->page_length ? $request->page_length : 5;
            $page_length_import = $request->page_length_import ? $request->page_length_import : 5;
            $this->param['role'] = $this->getRoleName();
            $this->param['title'] = 'KKB';
            $this->param['pageTitle'] = 'KKB';
            $this->param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit', 'Bukti Pembayaran Imbal Jasa'])->orderBy('name', 'DESC')->get();
            $tab_type = $request->get('tab_type');
            $temp_page = $request->page;

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
                ->whereNotNull('kredits.pengajuan_id')
                ->whereNull('kredits.imported_data_id')
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

            // set page number
            if ($tab_type != 'tab-kkb')
                $request->merge(['page' => 1]);
            else
                $request->merge(['page' => $temp_page]);

            if (is_numeric($page_length)) {
                if ($tab_type == 'tab-kkb')
                    $data = $data->paginate($page_length);
                else
                    $data = $data->paginate($page_length);
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
                                    // unset($data[$key]);
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
                $invoice = Document::where('kredit_id', $value->id)
                                            ->where('document_category_id', 7)
                                            ->first();

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

                $value->invoice = $invoice;
                $value->bukti_pembayaran = $buktiPembayaran;
                $value->penyerahan_unit = $penyerahanUnit;
                $value->stnk = $stnk;
                $value->bpkb = $bpkb;
                $value->polis = $polis;
                $value->imbal_jasa = $imbalJasa;
                $value->set_imbal_jasa = $setImbalJasa;
            }
            $this->param['data'] = $data;
            
            // imported data
            $imported = DB::table('imported_data AS import')
                ->select(
                    'import.name',
                    'import.tgl_po',
                    'import.tgl_realisasi',
                    'kredits.id',
                    'kredits.pengajuan_id',
                    'kredits.imported_data_id',
                    'kredits.kode_cabang',
                    'kkb.id AS kkb_id',
                    'kkb.user_id',
                    'kkb.tgl_ketersediaan_unit',
                    'kkb.id_tenor_imbal_jasa',
                    'kkb.nominal_realisasi',
                    'kkb.nominal_dp',
                    'kkb.nominal_imbal_jasa',
                    'kkb.nominal_pembayaran_imbal_jasa',
                    'po.merk',
                    'po.tipe',
                    'po.tahun_kendaraan',
                    'po.warna',
                    'po.keterangan',
                    'po.jumlah',
                    'po.harga',
                    \DB::raw("(SELECT COUNT(id) FROM document_categories) AS total_doc_requirement"),
                    \DB::raw('COALESCE(COUNT(d.id), 0) AS total_file_uploaded'),
                    \DB::raw('CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) AS total_file_confirmed'),
                    \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < (SELECT COUNT(id) FROM document_categories), 'in progress', 'done') AS status"),
                )
                ->join('kredits', 'kredits.imported_data_id', 'import.id')
                ->join('kkb', 'kkb.kredit_id', 'kredits.id')
                ->join('data_po AS po', 'po.imported_data_id', 'kredits.imported_data_id')
                ->leftJoin('documents AS d', 'd.kredit_id', 'kredits.id')
                ->groupBy([
                    'kredits.id',
                    'kredits.imported_data_id',
                    'kredits.kode_cabang',
                    'kkb.id_tenor_imbal_jasa',
                    'kkb.id',
                    'kkb.tgl_ketersediaan_unit',
                    'po.merk',
                    'po.tipe',
                    'po.tahun_kendaraan',
                    'po.warna',
                    'po.keterangan',
                    'po.jumlah',
                    'po.harga',
                ])
                ->when($request->tAwal && $request->tAkhir, function ($query) use ($request) {
                    return $query->whereBetween('kkb.tgl_ketersediaan_unit', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))]);
                })
                ->when($request->cabang,function($query,$cbg){
                    return $query->where('kredits.kode_cabang',$cbg);
                })
                ->orderBy('total_file_uploaded')
                ->orderBy('total_file_confirmed');

            if ($this->param['role_id'] == 2) {
                $imported = $imported->whereNull('kredits.pengajuan_id')
                                    ->whereNotNull('kredits.imported_data_id')
                                    ->whereNull('kkb.user_id')
                                    ->orWhere('kkb.user_id', $user_id)
                                    ->whereNull('kredits.pengajuan_id')
                                    ->whereNotNull('kredits.imported_data_id');
            }

            // set page number
            if ($tab_type != 'tab-import-kkb')
                $request->merge(['page' => 1]);
            else
                $request->merge(['page' => $temp_page]);

            if (is_numeric($page_length_import)) {
                if ($tab_type == 'tab-import-kkb')
                    $imported = $imported->paginate($page_length_import);
                else
                    $imported = $imported->paginate(5);
            }
            else
                $imported = $imported->get();

            // dd(DB::getQueryLog());
            foreach ($imported as $key => $value) {
                // retrieve cabang from api
                $value->cabang = 'undifined';
                $host = env('LOS_API_HOST');
                $apiURL = $host . '/kkb/get-cabang/'. $value->kode_cabang;

                $headers = [
                    'token' => env('LOS_API_TOKEN')
                ];

                try {
                    $response = Http::timeout(3)->withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

                    $statusCode = $response->status();
                    $responseBody = json_decode($response->getBody(), true);
                    // input file path
                    if ($responseBody) {
                        if (array_key_exists('cabang', $responseBody))
                            $value->cabang = $responseBody['cabang'];
                    }
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // return $e->getMessage();
                }

                // retrieve documents
                $buktiPembayaran = DB::table('documents AS d')
                                    ->where('kredit_id', $value->id)
                                    ->where('document_category_id', 1)
                                    ->first();

                $invoice = DB::table('documents AS d')
                                    ->where('kredit_id', $value->id)
                                    ->where('document_category_id', 7)
                                    ->first();

                $penyerahanUnit = DB::table('documents AS d')
                                    ->where('kredit_id', $value->id)
                                    ->where('document_category_id', 2)
                                    ->first();

                $stnk = DB::table('documents AS d')
                            ->where('kredit_id', $value->id)
                            ->where('document_category_id', 3)
                            ->first();

                $bpkb = DB::table('documents AS d')
                            ->where('kredit_id', $value->id)
                            ->where('document_category_id', 5)
                            ->first();

                $polis = DB::table('documents AS d')
                            ->where('kredit_id', $value->id)
                            ->where('document_category_id', 4)
                            ->first();

                $imbalJasa = DB::table('documents AS d')
                            ->where('kredit_id', $value->id)
                            ->where('document_category_id', 6)
                            ->first();

                $value->bukti_pembayaran = $buktiPembayaran;
                $value->invoice = $invoice;
                $value->penyerahan_unit = $penyerahanUnit;
                $value->stnk = $stnk;
                $value->bpkb = $bpkb;
                $value->polis = $polis;
                $value->imbal_jasa = $imbalJasa;
            }

            $this->param['imported'] = $imported;

            return view('pages.home', $this->param);
        } catch (\Exception $e) {
            return $e->getMessage();
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function getRoleName()
    {
        $token = \Session::get(config('global.user_token_session'));
        $user = User::select(
            'users.id',
            'users.role_id',
            'r.name AS role_name',
        )
            ->join('roles AS r', 'r.id', 'users.role_id')
            ->where('users.id', $token ? \Session::get(config('global.user_id_session')) : Auth::user()->id)
            ->first();

        return $user ? $user->role_name : '';
    }
}
