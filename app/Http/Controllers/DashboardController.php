<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Utils\PaginateController;
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
    private $losHeaders;
    private $losHost;

    function __construct()
    {
        $this->role_id = Session::get(config('global.role_id_session'));
        $this->losHost = config('global.los_api_host');
        $this->losHeaders = [
            'token' => config('global.los_api_token')
        ];
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

            // get all cabang
            $apiURL = $this->losHost . '/kkb/get-cabang';
            $allCabangArr = [];

            try {
                $response = Http::timeout(3)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);
                // input file path
                if ($responseBody) {
                    $allCabangArr = $responseBody;
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                return $e->getMessage();
            }

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
            $role = '';
            if ($user) {
                if (is_array($user)) {
                    $role = $user['role'];
                }
            }
            else {
                $role = 'vendor';
            }

            $user_id = $token ? $user['id'] : $user->id;
            $user_cabang = $token ? $user['kode_cabang'] : $user->kode_cabang;

            $apiCabang = $this->losHost . '/kkb/get-cabang';
            $this->losHeaders['Authorization'] = "Bearer $token";
            $api_req = Http::timeout(20)->withHeaders($this->losHeaders)->get($apiCabang);

            if (!$token)
                $user_id = 0; // vendor

            if (\Session::get(config('global.role_id_session')) != 3) {
                // Role selain vendor
                $data = Kredit::select(
                    'kredits.id',
                    \DB::raw("IF (kredits.pengajuan_id IS NOT NULL, 'data_kkb', 'data_import') AS kategori"),
                    'kredits.pengajuan_id',
                    'kredits.imported_data_id',
                    'kredits.kode_cabang',
                    'kkb.id AS kkb_id',
                    'kkb.tgl_ketersediaan_unit',
                    'kkb.id_tenor_imbal_jasa',
                    'kkb.nominal_realisasi',
                    'kkb.nominal_dp',
                    'kkb.nominal_imbal_jasa',
                    'kkb.nominal_pembayaran_imbal_jasa',
                    'import.name',
                    'import.tgl_po',
                    'import.tgl_realisasi',
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
                    ->join('kkb', 'kkb.kredit_id', 'kredits.id')
                    ->leftJoin('imported_data AS import', 'import.id', 'kredits.imported_data_id')
                    ->leftJoin('data_po AS po', 'po.imported_data_id', 'kredits.imported_data_id')
                    ->leftJoin('documents AS d', 'd.kredit_id', 'kredits.id')
                    ->groupBy([
                        'kredits.id',
                        'kredits.pengajuan_id',
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
                    ->when($role, function($query) use ($role, $user_id) {
                        if ($role == 'Staf Analis Kredit') {
                            $query->where('kkb.user_id', $user_id);
                        }
                        else {
                            $query->whereNotNull('kkb.user_id');
                        }
                    })
                    ->whereNotNull('kredits.pengajuan_id')
                    ->when(\Session::get(config('global.role_id_session')), function ($query) use ($request, $role) {
                        if (strtolower($role) != 'administrator' && strtolower($role) != 'kredit umum' && strtolower($role) != 'pemasaran' && strtolower($role) != 'spi') {
                            $query->where('kredits.kode_cabang', \Session::get(config('global.user_token_session')) ? 
                                \Session::get(config('global.user_kode_cabang_session')) : Auth::user()->kode_cabang);
                        }
                    })
                    ->when($request->tAwal && $request->tAkhir && $request->status, function ($query) use ($request) {
                        return $query->whereBetween('kredits.created_at', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))])
                            ->having('status', strtolower($request->status));
                    })
                    ->when($request->get('query'), function ($query) use ($request) {
                        return $query->where('import.name', 'like', '%' . $request->get('query') . '%');
                    })
                    ->when($request->cabang, function ($query, $cbg) {
                        return $query->having('kredits.kode_cabang', $cbg);
                    })
                    ->orWhereNotNull('kredits.is_continue_import')
                    ->when(\Session::get(config('global.role_id_session')), function ($query) use ($request, $role) {
                        if (strtolower($role) != 'administrator' && strtolower($role) != 'kredit umum' && strtolower($role) != 'pemasaran' && strtolower($role) != 'spi') {
                            $query->where('kredits.kode_cabang', \Session::get(config('global.user_token_session')) ? 
                                \Session::get(config('global.user_kode_cabang_session')) : Auth::user()->kode_cabang);
                        }
                    })
                    ->when($request->tAwal && $request->tAkhir && $request->status, function ($query) use ($request) {
                        return $query->whereBetween('kredits.created_at', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))])
                            ->having('status', strtolower($request->status));
                    })
                    ->when($request->get('query'), function ($query) use ($request) {
                        return $query->where('import.name', 'like', '%' . $request->get('query') . '%');
                    })
                    ->when($request->cabang, function ($query, $cbg) {
                        return $query->having('kredits.kode_cabang', $cbg);
                    })
                    ->when(\Session::get(config('global.role_id_session')), function ($query) use ($request, $role) {
                        if (strtolower($role) != 'administrator' && strtolower($role) != 'kredit umum' && strtolower($role) != 'pemasaran' && strtolower($role) != 'spi') {
                            $query->where('kredits.kode_cabang', \Session::get(config('global.user_token_session')) ? 
                                \Session::get(config('global.user_kode_cabang_session')) : Auth::user()->kode_cabang);
                        }
                    })
                    ->when($request->tAwal && $request->tAkhir && $request->status, function ($query) use ($request) {
                        return $query->whereBetween('kredits.created_at', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))])
                            ->having('status', strtolower($request->status));
                    })
                    ->when($request->get('query'), function ($query) use ($request) {
                        return $query->where('import.name', 'like', '%' . $request->get('query') . '%');
                    })
                    ->when($request->cabang, function ($query, $cbg) {
                        return $query->having('kredits.kode_cabang', $cbg);
                    })
                    ->orderBy('total_file_uploaded')
                    ->orderBy('total_file_confirmed');
            } else {
                // Vendor
                $data = Kredit::select(
                    'kredits.id',
                    \DB::raw("IF (kredits.pengajuan_id IS NOT NULL, 'data_kkb', 'data_import') AS kategori"),
                    'kredits.pengajuan_id',
                    'kredits.imported_data_id',
                    'kredits.kode_cabang',
                    'kkb.id AS kkb_id',
                    'kkb.tgl_ketersediaan_unit',
                    'kkb.id_tenor_imbal_jasa',
                    'kkb.nominal_realisasi',
                    'kkb.nominal_dp',
                    'kkb.nominal_imbal_jasa',
                    'kkb.nominal_pembayaran_imbal_jasa',
                    'import.name',
                    'import.tgl_po',
                    'import.tgl_realisasi',
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
                    ->join('kkb', 'kkb.kredit_id', 'kredits.id')
                    ->leftJoin('imported_data AS import', 'import.id', 'kredits.imported_data_id')
                    ->leftJoin('data_po AS po', 'po.imported_data_id', 'kredits.imported_data_id')
                    ->leftJoin('documents AS d', 'd.kredit_id', 'kredits.id')
                    ->groupBy([
                        'kredits.id',
                        'kredits.pengajuan_id',
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
                    ->when($request->tAwal && $request->tAkhir && $request->status, function ($query) use ($request) {
                        return $query->whereBetween('kredits.created_at', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))])
                            ->having('status', strtolower($request->status));
                    })
                    ->when($request->get('query'), function ($query) use ($request) {
                        return $query->where('import.name', 'like', '%' . $request->get('query') . '%');
                    })
                    ->when($request->cabang, function ($query, $cbg) {
                        return $query->having('kredits.kode_cabang', $cbg);
                    })
                    ->orderBy('total_file_uploaded')
                    ->orderBy('total_file_confirmed');
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
            } else
                $data = $data->get();

            // retrieve from api
            foreach ($data as $key => $value) {
                if ($value->kategori == 'data_kkb') {
                    $apiURL = $this->losHost . '/kkb/get-data-pengajuan/' . $value->pengajuan_id . '/' . $user_id;

                    try {
                        $response = Http::timeout(3)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

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
                        } else {
                            $response = Http::timeout(3)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

                            $statusCode = $response->status();
                            $responseBody = json_decode($response->getBody(), true);
                            if ($responseBody) {
                                if (array_key_exists('sppk', $responseBody))
                                    $responseBody['sppk'] = "/upload/$value->pengajuan_id/sppk/" . $responseBody['sppk'];
                                if (array_key_exists('po', $responseBody))
                                    $responseBody['po'] = "/upload/$value->pengajuan_id/po/" . $responseBody['po'];
                                if (array_key_exists('pk', $responseBody))
                                    $responseBody['pk'] = "/upload/$value->pengajuan_id/pk/" . $responseBody['pk'];
                            }
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
                        } else {
                            $value->detail = $responseBody;
                        }
                    } catch (\Illuminate\Http\Client\ConnectionException $e) {
                        // return $e->getMessage();
                    }
                } else {
                    $value->detail = null;
                    if (count($allCabangArr) > 0) {
                        for ($i=0; $i < count($allCabangArr); $i++) { 
                            if ($value->kode_cabang == $allCabangArr[$i]['kode_cabang']) {
                                $value->detail = $allCabangArr[$i];
                                break;
                            }
                        }
                    }
                }

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

                usleep(500 * 1000); // sleep for 0.5 millisec
            }

            $data_array = [];
            if ($request->status != null) {
                foreach ($data as $rows) {
                    if ($rows->status == $request->status) {
                        array_push($data_array, $rows);
                    }
                }
                $this->param['data'] = $this->paginate($data_array);
            } else {
                $this->param['data'] = $data;
            }

            // Search query
            $search_q = strtolower($request->get('query'));
            if ($search_q && $tab_type == 'tab-kkb') {
                foreach ($data as $key => $value) {
                    if ($value->kategori == 'data_kkb') {
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
            }

            $this->param['data'] = $data;

            if ($request->get('query') != null) {
                if (\Session::get(config('global.role_id_session')) != 3) {
                    $importedSearch = DB::table('imported_data AS import')
                        ->select(
                            'import.name',
                            'import.tgl_po',
                            'import.tgl_realisasi',
                            'kredits.id',
                            \DB::raw("IF (kredits.pengajuan_id IS NOT NULL, 'data_kkb', 'data_import') AS kategori"),
                            'kredits.pengajuan_id',
                            'kredits.kode_cabang',
                            'kredits.imported_data_id',
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
                        ->where('import.name', 'like', '%' . $request->get('query') . '%')
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
                        ->orderBy('total_file_uploaded')
                        ->orderBy('total_file_confirmed');

                    if ($this->param['role_id'] == 2) {
                        $importedSearch = $importedSearch->where('kredits.kode_cabang', $user_cabang)
                            ->whereNull('kredits.pengajuan_id')
                            ->whereNotNull('kredits.imported_data_id')
                            ->whereNull('kkb.user_id')
                            ->whereNull('kredits.is_continue_import')
                            ->when($request->tAwal && $request->tAkhir, function ($query) use ($request) {
                                return $query->whereBetween('kredits.created_at', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))]);
                            })
                            ->when($request->cabang, function ($query, $cbg) {
                                return $query->where('kredits.kode_cabang', $cbg);
                            })

                            ->orWhere('kkb.user_id', $user_id)
                            ->whereNull('kredits.pengajuan_id')
                            ->whereNotNull('kredits.imported_data_id')
                            ->whereNull('kredits.is_continue_import')
                            ->when($request->tAwal && $request->tAkhir, function ($query) use ($request) {
                                return $query->whereBetween('kredits.created_at', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))]);
                            })
                            ->when($request->cabang, function ($query, $cbg) {
                                return $query->where('kredits.kode_cabang', $cbg);
                            });
                    }
                    if ($tab_type != 'tab-import-kkb')
                        $request->merge(['page' => 1]);
                    else
                        $request->merge(['page' => $temp_page]);

                    if (is_numeric($page_length_import)) {
                        if ($tab_type == 'tab-import-kkb')
                            $importedSearch = $importedSearch->paginate($page_length_import);
                        else
                            $importedSearch = $importedSearch->paginate(5);
                    } else
                        $importedSearch = $importedSearch->get();

                    foreach ($importedSearch as $key => $value) {
                        // retrieve cabang from api
                        $value->cabang = 'undifined';
                        if (count($allCabangArr) > 0) {
                            for ($i=0; $i < count($allCabangArr); $i++) { 
                                if ($value->kode_cabang == $allCabangArr[$i]['kode_cabang']) {
                                    $value->cabang = $allCabangArr[$i]['cabang'];
                                    break;
                                }
                            }
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

                        usleep(500 * 1000); // sleep for 0.5 millisec
                    }

                    // data search
                    $apiDataPengajuanSearch = $this->losHost . '/kkb/get-data-pengajuan-search/' . $user_id . '?query=' . $request->get('query');
                    $api_req_pengajuan = Http::timeout(6)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiDataPengajuanSearch);
                    $responseDataPengajuanSearch = json_decode($api_req_pengajuan->getBody(), true);
                    $arr_response_search = $responseDataPengajuanSearch['data'];

                    $result_search = [];
                    for ($i = 0; $i < count($arr_response_search); $i++) {
                        $detail = $this->loadKreditById($arr_response_search[$i]['id_pengajuan']);
                        $detail['detail'] = $arr_response_search[$i];
                        array_push($result_search, $detail);
                    }

                    if ($tab_type != 'tab-kkb')
                        $request->merge(['page' => 1]);
                    else
                        $request->merge(['page' => $temp_page]);

                    $page = $temp_page;
                    $total = $responseDataPengajuanSearch['total']; //total items in array
                    $limit = $request->page_length ? $request->page_length : 5; //per page
                    $totalPages = ceil($total / $limit); //calculate total pages
                    $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
                    $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
                    $offset = ($page - 1) * $limit;
                    if ($offset < 0) $offset = 0;
                    $responseDataPengajuanSearch = array_slice($result_search, $offset, $limit);


                    $orders = PaginateController::paginate($responseDataPengajuanSearch, $limit, $page);

                } else {
                    $apiDataPengajuanSearch = $this->losHost . '/kkb/get-data-pengajuan-search/' . $user_id . '?query=' . $request->get('query');
                    $api_req_pengajuan = Http::timeout(6)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiDataPengajuanSearch);
                    $responseDataPengajuanSearch = json_decode($api_req_pengajuan->getBody(), true);
                    $arr_response_search = $responseDataPengajuanSearch['data'];

                    $result_search = [];
                    for ($i = 0; $i < count($arr_response_search); $i++) {
                        $detail = $this->loadKreditById($arr_response_search[$i]['id_pengajuan']);
                        $detail['detail'] = $arr_response_search[$i];
                        array_push($result_search, $detail);
                    }

                    if ($tab_type != 'tab-kkb')
                        $request->merge(['page' => 1]);
                    else
                        $request->merge(['page' => $temp_page]);

                    $page = $temp_page;
                    $total = count($responseDataPengajuanSearch); //total items in array
                    $limit = $request->page_length ? $request->page_length : 5; //per page
                    $totalPages = ceil($total / $limit); //calculate total pages
                    $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
                    $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
                    $offset = ($page - 1) * $limit;
                    if ($offset < 0) $offset = 0;
                    $responseDataPengajuanSearch = array_slice($result_search, $offset, $limit);


                    $orders = PaginateController::paginate($responseDataPengajuanSearch, $limit, $page);
                }
                $this->param['dataSearch'] = $orders;
                if (\Session::get(config('global.role_id_session')) != 3) {
                    $this->param['importedSearch'] = $importedSearch;
                }
            }


            // Search query
            $search_q = strtolower($request->get('query'));

            // imported data
            $imported = DB::table('imported_data AS import')
                ->select(
                    'import.name',
                    'import.tgl_po',
                    'import.tgl_realisasi',
                    'kredits.id',
                    \DB::raw("IF (kredits.pengajuan_id IS NOT NULL, 'data_kkb', 'data_import') AS kategori"),
                    'kredits.pengajuan_id',
                    'kredits.kode_cabang',
                    'kredits.imported_data_id',
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
                ->orderBy('total_file_uploaded')
                ->orderBy('total_file_confirmed');

            if ($this->param['role_id'] == 2) {
                $imported = $imported->where('kredits.kode_cabang', $user_cabang)
                    ->whereNull('kredits.pengajuan_id')
                    ->whereNotNull('kredits.imported_data_id')
                    ->whereNull('kkb.user_id')
                    ->whereNull('kredits.is_continue_import')
                    ->when($request->tAwal && $request->tAkhir, function ($query) use ($request) {
                        return $query->whereBetween('kredits.created_at', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))]);
                    })
                    ->when($request->cabang, function ($query, $cbg) {
                        return $query->where('kredits.kode_cabang', $cbg);
                    })
                    ->when($request->get('query'), function ($query) use ($request) {
                        return $query->where('import.name', 'like', '%' . $request->get('query') . '%');
                    })
                    ->orWhere('kkb.user_id', $user_id)
                    ->whereNull('kredits.pengajuan_id')
                    ->whereNotNull('kredits.imported_data_id')
                    ->whereNull('kredits.is_continue_import')
                    ->when($request->tAwal && $request->tAkhir, function ($query) use ($request) {
                        return $query->whereBetween('kredits.created_at', [date('y-m-d', strtotime($request->tAwal)), date('y-m-d', strtotime($request->tAkhir))]);
                    })
                    ->when($request->cabang, function ($query, $cbg) {
                        return $query->where('kredits.kode_cabang', $cbg);
                    })
                    ->when($request->get('query'), function ($query) use ($request) {
                        return $query->where('import.name', 'like', '%' . $request->get('query') . '%');
                    });
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
            } else
                $imported = $imported->get();

            foreach ($imported as $key => $value) {
                // retrieve cabang from api
                $value->cabang = 'undifined';
                if (count($allCabangArr) > 0) {
                    for ($i=0; $i < count($allCabangArr); $i++) { 
                        if ($value->kode_cabang == $allCabangArr[$i]['kode_cabang']) {
                            $value->cabang = $allCabangArr[$i]['cabang'];
                            break;
                        }
                    }
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

                usleep(500 * 1000); // sleep for 0.5 millisec
            }

            $this->param['imported'] = $imported;

            $this->losHost = env('LOS_API_HOST');
            $headers = [
                'token' => env('LOS_API_TOKEN')
            ];


            $apiCabang = $this->losHost . '/kkb/get-cabang/';
            $api_req = Http::timeout(6)->withHeaders($this->losHeaders)->get($apiCabang);
            $responseCabang = json_decode($api_req->getBody(), true);
            $param['dataCabang'] = $responseCabang;
            $arr_data = [];

            if ($responseCabang) {
                // for ($i = 0; $i < count($responseCabang); $i++) {
                foreach ($responseCabang as $key => $value) {
                    $kode_cabang = $value['kode_cabang'];
                    $cabang = $value['cabang'];
                    $dataChart = DB::table('documents')->select(
                        'k.id',
                        // DB::raw("IFNULL((SELECT COUNT(id) FROM documents where kredits.kode_cabang = $kode_cabang), 0) as cabang"),
                    )
                        ->leftJoin('kredits AS k', 'documents.kredit_id', 'k.id')
                        ->where('k.kode_cabang',  $kode_cabang)
                        ->groupBy('k.id')
                        ->count();
                    $d = [
                        'kode_cabang' => $kode_cabang,
                        'cabang' => $cabang,
                        'data' => $dataChart,
                    ];

                    array_push($arr_data, $d);
                }
            }

            return view('pages.home', $this->param);
        } catch (\Exception $e) {
            return $e->getMessage();
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

    public function loadKreditById($pengajuan_id)
    {
        $data = Kredit::select(
            'kredits.id',
            \DB::raw("IF (kredits.pengajuan_id IS NOT NULL, 'data_kkb', 'data_import') AS kategori"),
            'kredits.pengajuan_id',
            'kredits.imported_data_id',
            'kredits.kode_cabang',
            'kkb.id AS kkb_id',
            'kkb.tgl_ketersediaan_unit',
            'kkb.id_tenor_imbal_jasa',
            'kkb.nominal_realisasi',
            'kkb.nominal_dp',
            'kkb.nominal_imbal_jasa',
            'kkb.nominal_pembayaran_imbal_jasa',
            'import.name',
            'import.tgl_po',
            'import.tgl_realisasi',
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
            ->join('kkb', 'kkb.kredit_id', 'kredits.id')
            ->leftJoin('imported_data AS import', 'import.id', 'kredits.imported_data_id')
            ->leftJoin('data_po AS po', 'po.imported_data_id', 'kredits.imported_data_id')
            ->leftJoin('documents AS d', 'd.kredit_id', 'kredits.id')
            ->groupBy([
                'kredits.id',
                'kredits.pengajuan_id',
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
            ->where('kredits.pengajuan_id', $pengajuan_id)
            ->first();

        if ($data) {
            $invoice = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 7)
                ->first();

            $buktiPembayaran = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 1)
                ->first();

            $penyerahanUnit = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 2)
                ->first();

            $stnk = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 3)
                ->first();

            $polis = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 4)
                ->first();

            $bpkb = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 5)
                ->first();

            $imbalJasa = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 6)
                ->first();

            $setImbalJasa = DB::table('tenor_imbal_jasas')->find($data->id_tenor_imbal_jasa);

            $data->invoice = $invoice;
            $data->bukti_pembayaran = $buktiPembayaran;
            $data->penyerahan_unit = $penyerahanUnit;
            $data->stnk = $stnk;
            $data->bpkb = $bpkb;
            $data->polis = $polis;
            $data->imbal_jasa = $imbalJasa;
            $data->set_imbal_jasa = $setImbalJasa;
        }

        return $data;
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

    public function getChartData()
    {
        $apiCabang = $this->losHost . '/kkb/get-cabang/';
        $token = \Session::get(config('global.user_token_session'));
        $this->losHeaders['Authorization'] = "Bearer $token";
        $api_req = Http::timeout(6)->withHeaders($this->losHeaders)->get($apiCabang);
        $responseCabang = json_decode($api_req->getBody(), true);
        $dataCharts = [];

        if ($responseCabang) {
            // for ($i = 0; $i < count($responseCabang); $i++) {
            foreach ($responseCabang as $key => $value) {
                $kode_cabang = $value['kode_cabang'];
                $cabang = $value['cabang'];
                $dataKredits = Kredit::select(
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
                    ->having("status", 'done')
                    ->where('kode_cabang', $kode_cabang)
                    ->count();
                $dataImported = DB::table('imported_data AS import')
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
                    ->having("status", 'done')
                    ->where('kode_cabang', $kode_cabang)
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
                    ->count();

                $dataCabang = [
                    'kode_cabang' => $kode_cabang,
                    'cabang' => $cabang,
                    'total' => intval($dataKredits),
                ];

                array_push($dataCharts, $dataCabang);
            }

            return response()->json([
                'data' => $dataCharts
            ]);
        } else {
            return response()->json([
                'data' => null
            ]);
        }
    }
}
