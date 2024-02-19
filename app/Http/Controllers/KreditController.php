<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\KKB;
use App\Models\Role;
use App\Models\User;
use App\Models\Kredit;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Events\KreditBroadcast;
use App\Models\DocumentCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\NotificationTemplate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Utils\PaginateController;
use Illuminate\Support\Facades\Session;

class KreditController extends Controller
{
    private $logActivity;
    private $dashboardContoller;
    private $notificationController;
    private $penggunaController;
    private $param;
    private $losHeaders;
    private $losHost;

    function __construct()
    {
        $this->logActivity = new LogActivitesController;
        $this->dashboardContoller = new DashboardController;
        $this->notificationController = new NotificationController;
        $this->penggunaController = new PenggunaController;
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
            $this->param['role_id'] = \Session::get(config('global.role_id_session'));
            $this->param['staf_analisa_kredit_role'] = 'Staf Analis Kredit';
            $this->param['is_kredit_page'] = request()->is('kredit');
            $page_length = $request->page_length ? $request->page_length : 5;
            $page_length_import = $request->page_length_import ? $request->page_length_import : 5;
            $this->param['role'] = $this->dashboardContoller->getRoleName();
            $this->param['title'] = 'KKB';
            $this->param['pageTitle'] = 'KKB';
            $this->param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit', 'Bukti Pembayaran Imbal Jasa'])->orderBy('name', 'DESC')->get();
            $tab_type = $request->get('tab_type');
            $temp_page = $request->page;

            $token = \Session::get(config('global.user_token_session'));
            $this->losHeaders['Authorization'] = "Bearer $token";

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

            $user_cabang =  $user['kode_cabang'];
            if (!$token)
                $user_id = 0; // vendor

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

            if (\Session::get(config('global.role_id_session')) != 3) {
                // Role selain vendor
                $data = Kredit::select(
                    'kredits.id',
                    \DB::raw("IF (kredits.pengajuan_id IS NOT NULL, 'data_kkb', 'data_import') AS kategori"),
                    'kredits.pengajuan_id',
                    'kredits.imported_data_id',
                    'kredits.kode_cabang',
                    'kkb.id AS kkb_id',
                    'kkb.is_upload_kkb',
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
                    'kkb.is_upload_kkb',
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
                    'kkb.is_upload_kkb',
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

            if ($request->has('query')) {
                // role selain vendor
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
                            'kkb.is_upload_kkb',
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
                        $apiURL = $this->losHost . '/kkb/get-cabang/' . $value->kode_cabang;

                        try {
                            $response = Http::timeout(3)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

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

                        usleep(500 * 1000); // sleep for 0.5 millisec
                    }

                    // data search
                    $apiDataPengajuanSearch = $this->losHost . '/kkb/get-data-pengajuan-search/' . $user_id . '?query=' . $request->get('query');
                    $api_req_pengajuan = Http::timeout(6)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiDataPengajuanSearch);
                    $responseDataPengajuanSearch = json_decode($api_req_pengajuan->getBody(), true);

                    $arr_response_search = $responseDataPengajuanSearch ? $responseDataPengajuanSearch['data'] : null;

                    $result_search = [];
                    if ($responseDataPengajuanSearch) {
                        for ($i = 0; $i < count($arr_response_search); $i++) {
                            $detail = $this->loadKreditById($arr_response_search[$i]['id_pengajuan']);
                            $detail['detail'] = $arr_response_search[$i];
                            array_push($result_search, $detail);
                        }
                    }

                    if ($tab_type != 'tab-kkb')
                        $request->merge(['page' => 1]);
                    else
                        $request->merge(['page' => $temp_page]);

                    $page = $temp_page;
                    $total = $responseDataPengajuanSearch ? $responseDataPengajuanSearch['total'] : 0; //total items in array
                    $limit = $request->page_length ? $request->page_length : 5; //per page
                    $totalPages = ceil($total / $limit); //calculate total pages
                    $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
                    $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
                    $offset = ($page - 1) * $limit;
                    if ($offset < 0) $offset = 0;
                    $responseDataPengajuanSearch = array_slice($result_search, $offset, $limit);


                    $orders = PaginateController::paginate($responseDataPengajuanSearch, $limit, $page);
                    $lasd = [
                        'data' => $data,
                        'dataSearch' => $orders
                    ];
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
            }
            $this->param['imported'] = $imported;

            if ($request->has('query')) {
                $this->param['dataSearch'] = $orders;
                if (\Session::get(config('global.role_id_session')) != 3) {
                    $this->param['importedSearch'] = $importedSearch;
                }
            }

            $apiCabang = $this->losHost . '/kkb/get-cabang/';
            $api_req = Http::timeout(6)->withHeaders($this->losHeaders)->get($apiCabang);
            $responseCabang = json_decode($api_req->getBody(), true);


            $this->param['dataCabang'] = $responseCabang;
            return view('pages.kredit.index', $this->param);
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
            'kkb.is_upload_kkb',
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

    public function loadDataJson(Request $request)
    {
        try {
            $this->param['role_id'] = \Session::get(config('global.role_id_session'));
            $this->param['staf_analisa_kredit_role'] = 'Staf Analis Kredit';
            $this->param['is_kredit_page'] = $request->kredit_page;
            $page_length = $request->page_length ? $request->page_length : 5;
            $page_length_import = $request->page_length_import ? $request->page_length_import : 5;
            $this->param['role'] = $this->dashboardContoller->getRoleName();
            $this->param['title'] = 'KKB';
            $this->param['pageTitle'] = 'KKB';
            $this->param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit', 'Bukti Pembayaran Imbal Jasa'])->orderBy('name', 'DESC')->get();
            $tab_type = $request->get('tab_type');
            $temp_page = $request->page;

            $token = \Session::get(config('global.user_token_session'));
            $this->losHeaders['Authorization'] = "Bearer $token";

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

            $user_cabang =  $user['kode_cabang'];
            if (!$token)
                $user_id = 0; // vendor

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

            if (\Session::get(config('global.role_id_session')) != 3) {
                // Role selain vendor
                $data = Kredit::select(
                    'kredits.id',
                    \DB::raw("IF (kredits.pengajuan_id IS NOT NULL, 'data_kkb', 'data_import') AS kategori"),
                    'kredits.pengajuan_id',
                    'kredits.imported_data_id',
                    'kredits.kode_cabang',
                    'kkb.id AS kkb_id',
                    'kkb.is_upload_kkb',
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
                    'kkb.is_upload_kkb',
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
            $html = view('pages.kredit.partial._table', $this->param)->render();

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
                    'kkb.is_upload_kkb',
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

            if ($request->has('query')) {
                // role selain vendor
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
                            'kkb.is_upload_kkb',
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
                        $apiURL = $this->losHost . '/kkb/get-cabang/' . $value->kode_cabang;

                        try {
                            $response = Http::timeout(3)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

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

                        usleep(500 * 1000); // sleep for 0.5 millisec
                    }

                    // data search
                    $apiDataPengajuanSearch = $this->losHost . '/kkb/get-data-pengajuan-search/' . $user_id . '?query=' . $request->get('query');
                    $api_req_pengajuan = Http::timeout(6)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiDataPengajuanSearch);
                    $responseDataPengajuanSearch = json_decode($api_req_pengajuan->getBody(), true);

                    $arr_response_search = $responseDataPengajuanSearch ? $responseDataPengajuanSearch['data'] : null;

                    $result_search = [];
                    if ($responseDataPengajuanSearch) {
                        for ($i = 0; $i < count($arr_response_search); $i++) {
                            $detail = $this->loadKreditById($arr_response_search[$i]['id_pengajuan']);
                            $detail['detail'] = $arr_response_search[$i];
                            array_push($result_search, $detail);
                        }
                    }

                    if ($tab_type != 'tab-kkb')
                        $request->merge(['page' => 1]);
                    else
                        $request->merge(['page' => $temp_page]);

                    $page = $temp_page;
                    $total = $responseDataPengajuanSearch ? $responseDataPengajuanSearch['total'] : 0; //total items in array
                    $limit = $request->page_length ? $request->page_length : 5; //per page
                    $totalPages = ceil($total / $limit); //calculate total pages
                    $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
                    $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
                    $offset = ($page - 1) * $limit;
                    if ($offset < 0) $offset = 0;
                    $responseDataPengajuanSearch = array_slice($result_search, $offset, $limit);


                    $orders = PaginateController::paginate($responseDataPengajuanSearch, $limit, $page);
                    $lasd = [
                        'data' => $data,
                        'dataSearch' => $orders
                    ];
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
            }
            $this->param['imported'] = $imported;

            if ($request->has('query')) {
                $this->param['dataSearch'] = $orders;
                if (\Session::get(config('global.role_id_session')) != 3) {
                    $this->param['importedSearch'] = $importedSearch;
                }
            }

            $apiCabang = $this->losHost . '/kkb/get-cabang/';
            $api_req = Http::timeout(6)->withHeaders($this->losHeaders)->get($apiCabang);
            $responseCabang = json_decode($api_req->getBody(), true);


            $this->param['dataCabang'] = $responseCabang;


            $this->param['imported'] = $imported;
            $html_import = view('pages.kredit.partial.imported._table', $this->param)->render();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully load data',
                'html' => $html,
                'html_import' => $html_import,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan. ' . $e->getMessage(),
                'error' => $e,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan. ' . $e->getMessage(),
                'error' => $e,
            ]);
        }
    }

    public function getDataPO($pengajuan_id)
    {
        try {
            $token = \Session::get(config('global.user_token_session'));
            $this->losHeaders['Authorization'] = "Bearer $token";

            $apiURL = $this->losHost . '/kkb/get-data-pengajuan-by-id/' . $pengajuan_id;

            $response = Http::timeout(3)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

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

    public function getDataCabang($kode_cabang)
    {
        try {
            $host = env('BIO_INTERFACE_API_HOST');
            $apiURL = $host . '/v1/cabang/' . $kode_cabang;

            $response = Http::timeout(3)->withOptions(['verify' => false])->get($apiURL);

            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody) {
                if (array_key_exists('data', $responseBody)) {
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

    public function uploadTagihan(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 49;

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'tagihan_scan' => 'required|mimes:pdf|max:2048',
        ], [
            'required' => ':attribute harus diisi.',
            'mimes' => ':attribute harus berupa pdf',
            'max' => ':attribute maksimal 2 Mb',
        ], [
            'id_kkb' => 'Kredit',
            'tagihan_scan' => 'Scan berkas tagihan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $file = $request->file('tagihan_scan');
            $file->storeAs('public/tagihan', $file->hashName());
            $kkb = KKB::find($request->id_kkb);
            if ($kkb) {
                $kredit = Kredit::find($kkb->kredit_id);
                if ($kredit) {
                    if ($kredit->imported_data_id && !$kredit->is_continue_import) {
                        $kredit->is_continue_import = true;
                        $kredit->save();
                    }
                }
            }

            $document = new Document();
            $document->kredit_id = $kkb->kredit_id;
            $document->date = date('Y-m-d');
            $document->file = $file->hashName();
            $document->document_category_id  = 7;
            $document->save();

            // send notification
            $this->notificationController->send($action_id, $kkb->kredit_id);

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas tagihan.');

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
            event(new KreditBroadcast('event upload tagihan created'));

            return response()->json($response);
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
            'bukti_pembayaran_scan' => 'Scan berkas bukti pembayaran',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $user_id = \Session::get(config('global.user_id_session'));
            $file = $request->file('bukti_pembayaran_scan');
            $file->storeAs('public/dokumentasi-bukti-pembayaran', $file->hashName());
            $kkb = KKB::find($request->id_kkb);
            if ($kkb) {
                $kredit = Kredit::find($kkb->kredit_id);
                if ($kredit) {
                    if ($kredit->imported_data_id && !$kredit->is_continue_import) {
                        $kredit->is_continue_import = true;
                        $kredit->save();
                    }
                }
            }

            $document = new Document();
            $document->kredit_id = $kkb->kredit_id;
            $kredit = Kredit::find($kkb->kredit_id);
            $document->date = date('Y-m-d');
            $document->file = $file->hashName();
            $document->document_category_id  = 1;
            $document->save();

            if ($document) {
                $doc_inv = Document::where('kredit_id', $kkb->kredit_id)
                    ->where('document_category_id', 7)
                    ->first();
                if ($doc_inv) {
                    // Mengkonfirmasi berkas tagihan atau invoice
                    $doc_inv->is_confirm = true;
                    $doc_inv->confirm_at = date('Y-m-d');
                    $doc_inv->confirm_by = $user_id;

                    $doc_inv->save();
                }

                $kredit = Kredit::find($document->kredit_id);
                if ($kredit->imported_data_id) {
                    if (!$kredit->is_continue_import) {
                        $kredit->is_continue_import = true;
                        $kredit->save();
                    }
                }
                if ($kredit->imported_data_id && !$kkb->user_id) {
                    // set user id for kkb data
                    DB::table('kkb')->where('id', $kkb->id)->update([
                        'user_id' => $user_id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            // send notif
            $send_notif = $this->notificationController->send($action_id, $kkb->kredit_id);

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
                'notification_email' => $send_notif,
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
        $notif = '';

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

        DB::beginTransaction();
        try {
            $kkb = KKB::where('id', $request->id_kkb)->first();
            if ($kkb) {
                $kredit = Kredit::find($kkb->kredit_id);
                if ($kredit) {
                    if ($kredit->imported_data_id && !$kredit->is_continue_import) {
                        $kredit->is_continue_import = true;
                        $kredit->save();
                    }
                }
            }
            $kkb->tgl_ketersediaan_unit = date('Y-m-d', strtotime($request->date));
            $kkb->save();

            $this->logActivity->store('Pengguna ' . $request->name . ' mengatur tanggal ketersediaan unit.');

            // send notification
            $notif = $this->notificationController->send($action_id, $kkb->kredit_id);

            DB::commit();

            $status = 'success';
            $message = 'Berhasil menyimpan data';
            event(new KreditBroadcast('event created'));
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
                'notif' => $notif,
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

            // update upload penggunggah BPKB
            $updateIsUpload = KKB::where('id',$request->id_kkb)->first();
            $updateIsUpload->is_upload_kkb = $request->get('default-radio') == 'cabang' ? 'cabang' : 'vendor';
            $updateIsUpload->update();
            Session::put('is_upload_kkb',$request->get('default-radio') == 'cabang' ? 'cabang' : 'vendor');
            // update end upload penggunggah BPKB

            $kredit = Kredit::find($kkb->kredit_id);
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

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas nomor polis.');

            // send notification
            $this->notificationController->send($action_id, $request->id_kkb);

            // retrieve from api
            $dataPO = $this->getDataPO($kredit->pengajuan_id);
            $cabang = $this->getDataCabang($kredit->kode_cabang);

            // $notifTemplate = NotificationTemplate::find(9);

            // $this->notificationController->sendEmail($cabang['email'],  [
            //     'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
            //     'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
            //     'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
            //     'to' => 'Cabang '.$dataPO['cabang'],
            //     'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
            // ]);

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
            $this->notificationController->send($action_id, $request->id_kkb);

            // retrieve from api
            $dataPO = $this->getDataPO($kredit->pengajuan_id);
            $cabang = $this->getDataCabang($kredit->kode_cabang);
            // $notifTemplate = NotificationTemplate::find(10);

            // $this->notificationController->sendEmail($cabang['email'],  [
            //     'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
            //     'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
            //     'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
            //     'to' => 'Cabang '.$dataPO['cabang'],
            //     'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
            // ]);

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
            $this->notificationController->send($action_id, $request->id_kkb);

            $dataPO = $this->getDataPO($kredit->pengajuan_id);
            $cabang = $this->getDataCabang($kredit->kode_cabang);
            // $notifTemplate = NotificationTemplate::find(8);

            // $this->notificationController->sendEmail($cabang['email'],  [
            //     'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
            //     'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
            //     'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
            //     'to' => 'Cabang '.$dataPO['cabang'],
            //     'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
            // ]);

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
            if ($request->has('stnk_scan') || $request->has('polis_scan') || $request->has('bpkb_scan')) {
                if ($kkb) {
                    $kredit = Kredit::find($kkb->kredit_id);
                    if ($kredit) {
                        if ($kredit->imported_data_id && !$kredit->is_continue_import) {
                            $kredit->is_continue_import = true;
                            $kredit->save();
                        }
                    }
                }
            }

            // stnk
            if ($request->file('stnk_scan')) {
                $already_upload = Document::select('id')
                    ->where('kredit_id', $kkb->id)
                    ->where('document_category_id', 3)
                    ->first();

                if (!$already_upload) {
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

                    // save log
                    $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas.');
                }
            }

            // polis
            if ($request->file('polis_scan')) {
                $already_upload = Document::select('id')
                    ->where('kredit_id', $kkb->id)
                    ->where('document_category_id', 4)
                    ->first();

                if (!$already_upload) {
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
                    // $this->notificationController->send(10, $kkb->kredit_id);

                    // save log
                    $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas.');
                }
            }

            // bpkb
            if ($request->file('bpkb_scan')) {
                $already_upload = Document::select('id')
                    ->where('kredit_id', $kkb->id)
                    ->where('document_category_id', 5)
                    ->first();

                if (!$already_upload) {
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

                    // save log
                    $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas.');
                }
            }

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
        $send_notif = null;

        try {
            \DB::beginTransaction();
            $user_id = \Session::get(config('global.user_id_session'));
            // check is upload

            if (\Session::get(config('global.role_id_session')) == 2) {
                // Cabang
                $doc_cat_name = 'undifined';

                $kkb = KKB::where('id', $request->id_kkb)->first();
                if ($request->has('id_stnk') || $request->has('id_polis') || $request->has('id_bpkb')) {
                    if ($kkb) {
                        $kredit = Kredit::find($kkb->kredit_id);
                        if ($kredit) {
                            if ($kredit->imported_data_id && !$kredit->is_continue_import) {
                                $kredit->is_continue_import = true;
                                $kredit->save();
                            }
                        }
                    }
                }

                // stnk
                if ($request->has('id_stnk')) {
                    if (is_numeric($request->id_stnk) && $request->id_stnk != 0) {
                        $stnk = Document::find($request->id_stnk);
                        $docCategory = DocumentCategory::select('name')->find($stnk->document_category_id);
                        $doc_cat_name = $docCategory->name;

                        // send notification
                        if (!$stnk->is_confirm)
                            $send_notif = $this->notificationController->send(12, $stnk->kredit_id);

                        $stnk->is_confirm = 1;
                        $stnk->confirm_at = date('Y-m-d');
                        $stnk->confirm_by = \Session::get(config('global.user_id_session'));
                        $stnk->save();

                        $kredit = Kredit::find($stnk->kredit_id);
                        $kkb = KKB::where('kredit_id', $kredit->id)->first();

                        if ($kredit->imported_data_id) {
                            if (!$kredit->is_continue_import) {
                                $kredit->is_continue_import = true;
                                $kredit->save();
                            }
                        }
                        if ($kredit->imported_data_id && !$kkb->user_id) {
                            // set user id for kkb data
                            DB::table('kkb')->where('id', $kkb->id)->update([
                                'user_id' => $user_id,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }
                }

                // polis
                if ($request->has('id_polis')) {
                    if (is_numeric($request->id_polis) && $request->id_polis != 0) {
                        $polis = Document::find($request->id_polis);
                        $docCategory = DocumentCategory::select('name')->find($polis->document_category_id);
                        $doc_cat_name = $docCategory->name;

                        // send notification
                        if (!$polis->is_confirm)
                            $send_notif = $this->notificationController->send(13, $polis->kredit_id);

                        $polis->is_confirm = 1;
                        $polis->confirm_at = date('Y-m-d');
                        $polis->confirm_by = \Session::get(config('global.user_id_session'));
                        $polis->save();

                        $kredit = Kredit::find($polis->kredit_id);
                        $kkb = KKB::where('kredit_id', $kredit->id)->first();
                        if ($kredit->imported_data_id) {
                            if (!$kredit->is_continue_import) {
                                $kredit->is_continue_import = true;
                                $kredit->save();
                            }
                        }
                        if ($kredit->imported_data_id && !$kkb->user_id) {
                            // set user id for kkb data
                            DB::table('kkb')->where('id', $kkb->id)->update([
                                'user_id' => $user_id,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }
                }

                // bpkb
                if ($request->has('id_bpkb')) {
                    if (is_numeric($request->id_bpkb) && $request->id_bpkb != 0) {
                        $bpkb = Document::find($request->id_bpkb);
                        $docCategory = DocumentCategory::select('name')->find($bpkb->document_category_id);
                        $doc_cat_name = $docCategory->name;

                        // send notification
                        if (!$bpkb->is_confirm)
                            $send_notif = $this->notificationController->send(14, $bpkb->kredit_id);

                        $bpkb->is_confirm = 1;
                        $bpkb->confirm_at = date('Y-m-d');
                        $bpkb->confirm_by = \Session::get(config('global.user_id_session'));
                        $bpkb->save();

                        $kredit = Kredit::find($bpkb->kredit_id);
                        $kkb = KKB::where('kredit_id', $kredit->id)->first();
                        if ($kredit->imported_data_id) {
                            if (!$kredit->is_continue_import) {
                                $kredit->is_continue_import = true;
                                $kredit->save();
                            }
                        }
                        if ($kredit->imported_data_id && !$kkb->user_id) {
                            // set user id for kkb data
                            DB::table('kkb')->where('id', $kkb->id)->update([
                                'user_id' => $user_id,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }
                }

                $this->logActivity->store('Pengguna ' . $request->name . ' mengkonfirmasi berkas ' . $doc_cat_name . '.');

                \DB::commit();
                $status = 'success';
                $message = 'Berhasil mengkonfirmasi berkas';
            } else {
                if ($request->get('is_upload') == 'cabang') {
                    $doc_cat_name = 'undifined';
                    $kkb = KKB::where('id', $request->id_kkb)->first();
                    if ($request->has('id_stnk') || $request->has('id_polis') || $request->has('id_bpkb')) {
                        if ($kkb) {
                            $kredit = Kredit::find($kkb->kredit_id);
                            if ($kredit) {
                                if ($kredit->imported_data_id && !$kredit->is_continue_import) {
                                    $kredit->is_continue_import = true;
                                    $kredit->save();
                                }
                            }
                        }
                    }

                    // stnk
                    if ($request->has('id_stnk')) {
                        if (is_numeric($request->id_stnk) && $request->id_stnk != 0) {
                            $stnk = Document::find($request->id_stnk);
                            $docCategory = DocumentCategory::select('name')->find($stnk->document_category_id);
                            $doc_cat_name = $docCategory->name;

                            // send notification
                            if (!$stnk->is_confirm)
                                $send_notif = $this->notificationController->send(12, $stnk->kredit_id);

                            $stnk->is_confirm = 1;
                            $stnk->confirm_at = date('Y-m-d');
                            $stnk->confirm_by = \Session::get(config('global.user_id_session'));
                            $stnk->save();

                            $kredit = Kredit::find($stnk->kredit_id);
                            $kkb = KKB::where('kredit_id', $kredit->id)->first();

                            if ($kredit->imported_data_id) {
                                if (!$kredit->is_continue_import) {
                                    $kredit->is_continue_import = true;
                                    $kredit->save();
                                }
                            }
                            if ($kredit->imported_data_id && !$kkb->user_id) {
                                // set user id for kkb data
                                DB::table('kkb')->where('id', $kkb->id)->update([
                                    'user_id' => $user_id,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]);
                            }
                        }
                    }

                    // polis
                    if ($request->has('id_polis')) {
                        if (is_numeric($request->id_polis) && $request->id_polis != 0) {
                            $polis = Document::find($request->id_polis);
                            $docCategory = DocumentCategory::select('name')->find($polis->document_category_id);
                            $doc_cat_name = $docCategory->name;

                            // send notification
                            if (!$polis->is_confirm)
                                $send_notif = $this->notificationController->send(13, $polis->kredit_id);

                            $polis->is_confirm = 1;
                            $polis->confirm_at = date('Y-m-d');
                            $polis->confirm_by = \Session::get(config('global.user_id_session'));
                            $polis->save();

                            $kredit = Kredit::find($polis->kredit_id);
                            $kkb = KKB::where('kredit_id', $kredit->id)->first();
                            if ($kredit->imported_data_id) {
                                if (!$kredit->is_continue_import) {
                                    $kredit->is_continue_import = true;
                                    $kredit->save();
                                }
                            }
                            if ($kredit->imported_data_id && !$kkb->user_id) {
                                // set user id for kkb data
                                DB::table('kkb')->where('id', $kkb->id)->update([
                                    'user_id' => $user_id,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]);
                            }
                        }
                    }

                    // bpkb
                    if ($request->has('id_bpkb')) {
                        if (is_numeric($request->id_bpkb) && $request->id_bpkb != 0) {
                            $bpkb = Document::find($request->id_bpkb);
                            $docCategory = DocumentCategory::select('name')->find($bpkb->document_category_id);
                            $doc_cat_name = $docCategory->name;

                            // send notification
                            if (!$bpkb->is_confirm)
                                $send_notif = $this->notificationController->send(14, $bpkb->kredit_id);

                            $bpkb->is_confirm = 1;
                            $bpkb->confirm_at = date('Y-m-d');
                            $bpkb->confirm_by = \Session::get(config('global.user_id_session'));
                            $bpkb->save();

                            $kredit = Kredit::find($bpkb->kredit_id);
                            $kkb = KKB::where('kredit_id', $kredit->id)->first();
                            if ($kredit->imported_data_id) {
                                if (!$kredit->is_continue_import) {
                                    $kredit->is_continue_import = true;
                                    $kredit->save();
                                }
                            }
                            if ($kredit->imported_data_id && !$kkb->user_id) {
                                // set user id for kkb data
                                DB::table('kkb')->where('id', $kkb->id)->update([
                                    'user_id' => $user_id,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]);
                            }
                        }
                    }

                    $this->logActivity->store('Pengguna ' . $request->name . ' mengkonfirmasi berkas ' . $doc_cat_name . '.');

                    \DB::commit();
                    $status = 'success';
                    $message = 'Berhasil mengkonfirmasi berkas';
                }else{
                    $status = 'failed';
                    $message = 'Hanya cabang yang bisa melakukan konfirmasi';
                }
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
                'notification_email' => $send_notif,
            ];

            event(new KreditBroadcast('confirm berkas'));

            return response()->json($response);
        }
    }

    public function confirmDocumentVendor(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 5;

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
                if ($kredit) {
                    if ($kredit->imported_data_id && !$kredit->is_continue_import) {
                        $kredit->is_continue_import = true;
                        $kredit->save();
                    }
                }
                $docCategory = DocumentCategory::select('name')->find($request->category_id);
                $document->is_confirm = 1;
                $document->confirm_at = date('Y-m-d');
                $document->confirm_by = \Session::get(config('global.user_id_session'));
                $document->save();

                $dataPO = $this->getDataPO($kredit->pengajuan_id);
                $cabang = $this->getDataCabang($kredit->kode_cabang);
                // send notification
                $this->notificationController->send($action_id, $kredit->id);

                // $notifTemplate = NotificationTemplate::find(4);

                // $this->notificationController->sendEmail($cabang['email'],  [
                //     'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
                //     'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
                //     'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
                //     'to' => 'Cabang '.$dataPO['cabang'],
                //     'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
                // ]);

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
        $action_id = 8;
        $send_notif = null;

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
                $user_id = \Session::get(config('global.user_id_session'));
                $document = Document::find($request->id);
                $docCategory = DocumentCategory::select('name')->find($request->category_id);
                // $kkb = KKB::where('id', $document->id_kkb)->first();
                $kredit = Kredit::find($document->kredit_id);
                $document->is_confirm = 1;
                $document->confirm_at = date('Y-m-d');
                $document->confirm_by = \Session::get(config('global.user_id_session'));
                $document->save();

                $kredit = Kredit::find($document->kredit_id);
                $kkb = KKB::where('kredit_id', $kredit->id)->first();
                if ($kredit->imported_data_id) {
                    if (!$kredit->is_continue_import) {
                        $kredit->is_continue_import = true;
                        $kredit->save();
                    }
                }
                if ($kredit->imported_data_id && !$kkb->user_id) {
                    // set user id for kkb data
                    DB::table('kkb')->where('id', $kkb->id)->update([
                        'user_id' => $user_id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                if ($request->category_id == 2) {
                    $vendor = User::select('users.email', 'v.name')
                        ->join('vendors AS v', 'v.id', 'users.vendor_id')
                        ->where('users.role_id', 3)
                        ->first();

                    if ($vendor) {
                        // send notif
                        $send_notif = $this->notificationController->send($action_id, $kkb->kredit_id);
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
                'notification_email' => $send_notif,
            ];

            event(new KreditBroadcast('event created'));

            return response()->json($response);
        }
    }

    public function show($id, Request $request)
    {
        $status = '';
        $message = '';
        $data = null;
        try {
            $is_import = $request->has('is_import');
            $token = \Session::get(config('global.user_token_session'));
            $this->losHeaders['Authorization'] = "Bearer $token";
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

            $responseBody = null;
            $imported_data = null;

            if ($is_import) {
                // retrieve from imported_data
                $imported_data = DB::table('kredits AS k')
                    ->select(
                        'import.name',
                        'import.tgl_po',
                        'import.tgl_realisasi',
                        'k.id AS kredit_id',
                        'k.kode_cabang AS kode_cabang',
                        'po.merk',
                        'po.tipe',
                        'po.tahun_kendaraan',
                        'po.warna',
                        'po.keterangan',
                        'po.jumlah',
                        'po.harga'
                    )
                    ->join('imported_data AS import', 'import.id', 'k.imported_data_id')
                    ->join('data_po AS po', 'po.imported_data_id', 'import.id')
                    ->where('k.id', $id)
                    ->first();

                if ($imported_data) {
                    $imported_data->cabang = 'undifined';
                    $apiURL = $this->losHost . '/kkb/get-cabang/' . $imported_data->kode_cabang;

                    try {
                        $response = Http::timeout(3)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

                        $statusCode = $response->status();
                        $responseBody = json_decode($response->getBody(), true);
                        // input file path
                        if ($responseBody) {
                            if (array_key_exists('cabang', $responseBody))
                                $imported_data->cabang = $responseBody['cabang'];
                        }
                    } catch (\Illuminate\Http\Client\ConnectionException $e) {
                        // return $e->getMessage();
                    }
                }
            } else {
                // retrieve from api
                $apiURL = $this->losHost . '/kkb/get-data-pengajuan/' . $kredit->pengajuan_id . '/' . $user_id;

                try {
                    $response = Http::withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

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
            }

            // retrieve karyawan data
            $karyawan = $this->penggunaController->getKaryawan($user_nip);


            if (is_array($karyawan)) {
                if (array_key_exists('error', $karyawan))
                    $karyawan = null;
            } else {
                $karyawan = null;
            }

            $data = [
                'documents' => $document,
                'pengajuan' => $responseBody,
                'import' => $imported_data,
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
        $action_id = 15;
        $send_notif = null;

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
            $user_id = \Session::get(config('global.user_id_session'));
            $kredit = Kredit::find($request->id_kkbimbaljasa);
            $kkb = KKB::where('kredit_id', $request->id_kkbimbaljasa)->first();
            $file = $request->file('file_imbal_jasa');
            $file->storeAs('public/dokumentasi-imbal-jasa', $file->hashName());
            $document = new Document();
            $document->kredit_id = $request->id_kkbimbaljasa;
            $document->date = Carbon::now();
            $document->file = $file->hashName();
            $document->document_category_id  = 6;
            $document->save();

            if ($kredit->imported_data_id) {
                if (!$kredit->is_continue_import) {
                    $kredit->is_continue_import = true;
                    $kredit->save();
                }
            }
            if ($kredit->imported_data_id && !$kkb->user_id) {
                // set user id for kkb data
                DB::table('kkb')->where('id', $kkb->id)->update([
                    'user_id' => $user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $this->logActivity->store('Pengguna ' . $request->name . ' mengunggah berkas imbal jasa.');

            $vendor = User::select('users.email', 'v.name')
                ->join('vendors AS v', 'v.id', 'users.vendor_id')
                ->where('users.role_id', 3)
                ->first();

            if ($vendor) {
                // send notif
                $send_notif = $this->notificationController->send($action_id, $kkb->kredit_id);
            }

            $status = 'success';
            $message = 'Berhasil mengupload berkas imbal jasa.';
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
                'notification_email' => $send_notif,
            ];

            event(new KreditBroadcast('event created'));

            return response()->json($response);
        }
    }

    public function confirmUploadUImbalJasa(Request $request)
    {
        $status = '';
        $message = '';
        $action_id = 50;

        try {
            $document = Document::find($request->id);
            $document->is_confirm = true;
            $document->confirm_at = Carbon::now();
            $document->confirm_by = \Session::get(config('global.user_id_session'));
            $document->save();

            if ($document) {
                $kredit = Kredit::find($document->kredit_id);
                if ($kredit->imported_data_id) {
                    $kkb = KKB::where('kredit_id', $document->kredit_id)->first();
                    if (!$kredit->is_continue_import) {
                        $kredit->is_continue_import = true;
                        $kredit->save();
                    }
                    DB::table('kkb')
                        ->where('kredit_id', $document->kredit_id)
                        ->update([
                            'nominal_pembayaran_imbal_jasa' => $kkb->nominal_imbal_jasa,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }

            $this->logActivity->store('Pengguna ' . Auth::user()->name . ' mengkonfirmasi berkas imbal jasa.');

            // send notification
            $this->notificationController->send($action_id, $document->kredit_id);

            $dataPO = $this->getDataPO($kredit->pengajuan_id);
            $cabang = $this->getDataCabang($kredit->kode_cabang);

            // $notifTemplate = NotificationTemplate::find(15);

            // $this->notificationController->sendEmail($cabang['email'],  [
            //     'title' => $notifTemplate ? $notifTemplate->title : 'undifined',
            //     'no_po' => array_key_exists('no_po', $dataPO) ? $dataPO['no_po'] : 'undifined',
            //     'nama_debitur' => array_key_exists('nama', $dataPO) ? $dataPO['nama'] : 'undifined',
            //     'to' => 'Cabang '.$dataPO['cabang'],
            //     'body' => $notifTemplate ? $notifTemplate->content : 'undifined'
            // ]);

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
                'd' => $document
            ];

            event(new KreditBroadcast('event created'));

            return response()->json($response);
        }
    }
}
