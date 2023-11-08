<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kredit;
use App\Models\Target;
use App\Models\Document;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\DocumentCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Utils\PaginateController;
use App\Http\Controllers\Master\PenggunaController;

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
            $tanggalAwal = date('Y') . '-' . date('m') . '-01';
            $hari_ini = now();
            $tAwal = date("Y-m-d", strtotime($request->tAwal));
            $tAkhir = date("Y-m-d", strtotime($request->tAkhir));
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

            $dataDashboard = Kredit::select(
                \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < (SELECT COUNT(id) FROM document_categories), 'in progress', 'done') AS status"),
            )
                ->join('kkb', 'kkb.kredit_id', 'kredits.id')
                ->leftJoin('documents AS d', 'd.kredit_id', 'kredits.id')
                ->groupBy([
                    'kredits.id',
                    'kredits.pengajuan_id',
                    'kredits.imported_data_id',
                    'kredits.kode_cabang',
                    'kkb.id_tenor_imbal_jasa',
                    'kkb.id',
                    'kkb.tgl_ketersediaan_unit',
                ]);
                // card kkb
                $this->param['kkbProses'] = $dataDashboard->having('status', 'in progress')->count();
                $this->param['kkbSelesai'] = $dataDashboard->having('status', 'done')->count();
                $this->param['kkbBulanIni'] = $dataDashboard->whereBetween('kkb.tgl_ketersediaan_unit', [$tanggalAwal, $hari_ini])->count();
                $this->param['kkbImported'] = $dataDashboard->where('kredits.imported_data_id', '!=', null )->count();


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
                ->whereBetween('notifications.created_at', [$tanggalAwal, $hari_ini])
                ->when($tAwal && $tAkhir, function ($query) use ($tAwal, $tAkhir) {
                    return $query->whereBetween('notifications.created_at', [$tAwal, $tAkhir]);
                })
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

            // data registrasi
            $this->param['total_waiting'] = DB::table('asuransi')->where('status', 'waiting approval')->count();
            $this->param['total_approved'] = DB::table('asuransi')
            ->where('status', 'approved')->count();
            $this->param['total_revisi'] = DB::table('asuransi')
            ->where('status', 'revition')
            ->count();
            $this->param['total_sended'] = DB::table('asuransi')->where('status', 'sended')->count();
            $this->param['total_canceled'] = DB::table('asuransi')->where('status', 'canceled')->count();

            // data klaim
            $this->param['yangDibatalkan'] = DB::table('pengajuan_klaim')->where('status', 'canceled')->count();
            $this->param['sudahKlaim'] = DB::table('pengajuan_klaim')->where('stat_klaim', '3')->count();

            $dataAsuransi = DB::table('asuransi')->get();
            $dataKlaim = DB::table('pengajuan_klaim')->select('asuransi_id')->get();

            $belumKlaim = 0;

            foreach ($dataAsuransi as $asuransi) {
                $asuransiId = $asuransi->id;

                $adaDiKlaim = $dataKlaim->contains('asuransi_id', $asuransiId);

                if (!$adaDiKlaim) {
                    $belumKlaim++;
                }
            }

            $this->param['belumKlaim'] = $belumKlaim;

            // data chart premi
            $this->param['dataYangSudahDibayar'] = DB::table('pembayaran_premi_detail')
            ->select('asuransi_id')
            ->distinct()
            ->pluck('asuransi_id')
            ->toArray();

            $this->param['dataAsuransiChart'] = DB::table('asuransi')
            ->select('id')
            ->pluck('id')
            ->toArray();


            // data new chartt pembayaran premi
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $role_id = \Session::get(config('global.role_id_session'));
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

            $host = env('LOS_API_HOST');
            $headers = [
                'token' => env('LOS_API_TOKEN')
            ];


            // $dataAsuransi = DB::table('asuransi')->get();
            $dataPembayaranPremi = DB::table('pembayaran_premi_detail')->select('asuransi_id')->get();

            $belumBayar = 0;
            $sudahBayar = 0;

            foreach ($dataAsuransi as $asuransi) {
                $asuransiId = $asuransi->id;

                $adaDiPremi = $dataPembayaranPremi->contains('asuransi_id', $asuransiId);

                if (!$adaDiPremi) {
                    $belumBayar++;
                }
                else {
                    $sudahBayar++;
                }
            }

            $this->param['sudahBayar'] = $sudahBayar;
            $this->param['belumBayar'] = $belumBayar;


            $user_cabang = $token ? $user['kode_cabang'] : $user->kode_cabang;
            $host = env('LOS_API_HOST');
            $headers = [
                'token' => env('LOS_API_TOKEN')
            ];

            $apiCabang = $host . '/kkb/get-cabang/';
            $api_req = Http::timeout(6)->withHeaders($headers)->get($apiCabang);
            $responseCabang = json_decode($api_req->getBody(), true);

            $this->param['dataCabang'] = $responseCabang;
            if (!$token)
                $user_id = 0;

            // Dashboard Asuransi
            // Registrasi
            $registered = 0;
            $not_registered = 0;
            $belum_registrasi = 0;
            // Klaim
            $total_sudah_klaim = 0;
            $total_belum_klaim = 0;
            $host = env('LOS_API_HOST');
            $headers = [
                'token' => env('LOS_API_TOKEN')
            ];

            if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
                // Registrasi
                $registered = DB::table('asuransi')
                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                ->select('asuransi.id')
                                ->where('asuransi.registered', 1)
                                ->where('k.is_asuransi', true)
                                ->where('k.kode_cabang', $user_cabang)
                                ->count();

                $not_registered = DB::table('asuransi')
                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                ->select('asuransi.id')
                                ->where('asuransi.registered', 0)
                                ->where('k.is_asuransi', true)
                                ->where('k.kode_cabang', $user_cabang)
                                ->count();
                // retrieve from api
                $apiURL = "$host/v1/get-list-pengajuan";

                try {
                    $response = Http::timeout(60)
                                    ->withHeaders($headers)
                                    ->withOptions(['verify' => false])
                                    ->get($apiURL, [
                                        'kode_cabang' => $user_cabang,
                                        'user' => 'all',
                                    ]);

                    $statusCode = $response->status();
                    $responseBody = json_decode($response->getBody(), true);

                    if ($responseBody) {
                        if(array_key_exists('data', $responseBody)) {
                            if (array_key_exists('data', $responseBody['data'])) {
                                $data = $responseBody['data']['data'];

                                foreach ($data as $key => $value) {
                                    // retrieve jenis_asuransi
                                    $jenis_asuransi = DB::table('mst_jenis_asuransi')
                                                        ->select('id', 'jenis')
                                                        ->where('jenis_kredit', $value['skema_kredit'])
                                                        ->orderBy('jenis')
                                                        ->get();

                                    foreach ($jenis_asuransi as $key2 => $value2) {
                                        // retrieve asuransi data
                                        $asuransi = DB::table('asuransi')
                                                    ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                                    ->leftJoin('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                                    ->select(
                                                        'asuransi.registered',
                                                    )
                                                    ->where('asuransi.jenis_asuransi_id', $value2->id);

                                        if ($request->has('dari') && $request->has('sampai')) {
                                            $tAwal = date('Y-m-d', strtotime($request->get('tAwal')));
                                            $tAkhir = date('Y-m-d', strtotime($request->get('tAkhir')));
                                            $asuransi = $asuransi->orWhereBetween('asuransi.created_at', [$tAwal, $tAkhir])
                                                                ->where('asuransi.jenis_asuransi_id', $value2->id);
                                        }

                                        $asuransi = $asuransi->groupBy('no_pk')
                                                            ->orderBy('no_aplikasi')
                                                            ->first();
                                        if (!$asuransi) {
                                            $belum_registrasi++;
                                        }
                                        $value2->asuransi = $asuransi;
                                    }
                                    $data[$key]['jenis_asuransi'] = $jenis_asuransi;
                                }
                            }
                        }
                    } else {
                    }
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // return $e->getMessage();
                }

                // Klaim
                $total_sudah_klaim = DB::table('asuransi')
                                    ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                    ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                    ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                    ->select('asuransi.id')
                                    ->where('asuransi.registered', 1)
                                    ->where('k.is_asuransi', true)
                                    ->where('k.kode_cabang', $user_cabang)
                                    ->whereRaw('asuransi.id IN (SELECT asuransi_id FROM pengajuan_klaim)')
                                    ->count();

                $total_belum_klaim = DB::table('asuransi')
                                    ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                    ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                    ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                    ->select('asuransi.id')
                                    ->where('asuransi.registered', 1)
                                    ->where('k.is_asuransi', true)
                                    ->where('k.kode_cabang', $user_cabang)
                                    ->whereRaw('asuransi.id NOT IN (SELECT asuransi_id FROM pengajuan_klaim)')
                                    ->count();
            } else {
                // Penyelia & staf
                // retrieve from api
                $data_registrasi = [];
                $apiURL = "$host/v1/get-list-pengajuan";

                try {
                    $response = Http::timeout(60)
                                    ->withHeaders($headers)
                                    ->withOptions(['verify' => false])
                                    ->get($apiURL, [
                                        'kode_cabang' => $user_cabang,
                                        'user' => $user_id,
                                    ]);

                    $statusCode = $response->status();
                    $responseBody = json_decode($response->getBody(), true);

                    if ($responseBody) {
                        if(array_key_exists('data', $responseBody)) {
                            if (array_key_exists('data', $responseBody['data'])) {
                                $data = $responseBody['data']['data'];

                                foreach ($data as $key => $value) {
                                    $nip = $value['karyawan']['nip'];
                                    $nama = $value['karyawan']['nama'];
                                    // retrieve jenis_asuransi
                                    $jenis_asuransi = DB::table('mst_jenis_asuransi')
                                                        ->select('id', 'jenis')
                                                        ->where('jenis_kredit', $value['skema_kredit'])
                                                        ->orderBy('jenis')
                                                        ->get();
                                    $jml_asuransi = $jenis_asuransi ? count($jenis_asuransi) : 0;
                                    $jml_diproses = 0;

                                    foreach ($jenis_asuransi as $key2 => $value2) {
                                        // retrieve asuransi data
                                        $asuransi = DB::table('asuransi')
                                                    ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                                    ->leftJoin('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                                    ->select(
                                                        'asuransi.registered',
                                                    )
                                                    ->where('asuransi.jenis_asuransi_id', $value2->id);

                                        $asuransi = $asuransi->groupBy('no_pk')
                                                            ->orderBy('no_aplikasi')
                                                            ->first();
                                        if (!$asuransi) {
                                            $belum_registrasi++;
                                        }
                                        else {
                                            $registered += $asuransi->registered ? 1 : 0;
                                            $not_registered += $asuransi->registered ? 0 : 1;
                                        }
                                        $value2->asuransi = $asuransi;
                                    }
                                    $data[$key]['jenis_asuransi'] = $jenis_asuransi;
                                    $d = [
                                        'nip' => $nip,
                                        'nama' => $nama,
                                        'jml_asuransi' => $jml_asuransi,
                                        'jml_diproses' => $jml_diproses,
                                    ];

                                    array_push($data_registrasi, $d);
                                }
                            }
                        }
                    } else {
                    }
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // return $e->getMessage();
                }

                $result = $this->group_by("nip", $data_registrasi);
                $finalResult = [];
                if ($result) {
                    foreach ($result as $key => $value) {
                        $jml_asuransi = 0;
                        $jml_diproses = 0;
                        for ($i=0; $i < count($value); $i++) {
                            $jml_asuransi += $value[$i]['jml_asuransi'];
                            $jml_diproses += $value[$i]['jml_diproses'];
                        };
                        $final_d = [
                            'nip' => $key,
                            'nama' => $value[0]['nama'],
                            'jml_asuransi' => $jml_asuransi,
                            'jml_diproses' => $jml_diproses,
                        ];

                        array_push($finalResult, $final_d);
                    }
                }

                // Klaim
                $total_sudah_klaim = DB::table('asuransi')
                                    ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                    ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                    ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                    ->select('asuransi.id')
                                    ->where('asuransi.registered', 1)
                                    ->where('k.is_asuransi', true)
                                    ->when($role, function($query) use ($role, $user_id) {
                                        if ($role == 'Staf Analis Kredit') {
                                            $query->where('asuransi.user_id', $user_id);
                                        }
                                        else {
                                            $query->where('asuransi.penyelia_id', $user_id);
                                        }
                                    })
                                    ->whereRaw('asuransi.id IN (SELECT asuransi_id FROM pengajuan_klaim)')
                                    ->count();

                $total_belum_klaim = DB::table('asuransi')
                                    ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                    ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                    ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                    ->select('asuransi.id')
                                    ->where('asuransi.registered', 1)
                                    ->where('k.is_asuransi', true)
                                    ->when($role, function($query) use ($role, $user_id) {
                                        if ($role == 'Staf Analis Kredit') {
                                            $query->where('asuransi.user_id', $user_id);
                                        }
                                        else {
                                            $query->where('asuransi.penyelia_id', $user_id);
                                        }
                                    })
                                    ->whereRaw('asuransi.id NOT IN (SELECT asuransi_id FROM pengajuan_klaim)')
                                    ->count();
            }
            $this->param['registered'] = $registered;
            $this->param['not_registered'] = $not_registered;
            $this->param['belum_registrasi'] = $belum_registrasi;
            $this->param['total_sudah_klaim'] = $total_sudah_klaim;
            $this->param['total_belum_klaim'] = $total_belum_klaim;

            return view('pages.home', $this->param);
        } catch (\Exception $e) {
            return $e->getMessage();
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

    public function detailRegistrasi() {
        $token = \Session::get(config('global.user_token_session'));
        $user = $token ? $this->getLoginSession() : Auth::user();
        $user_cabang = $token ? $user['kode_cabang'] : $user->kode_cabang;
        $role_id = \Session::get(config('global.role_id_session'));
        $role = '';
        if ($user) {
            if (is_array($user)) {
                $role = $user['role'];
            }
        }
        else {
            $role = 'vendor';
        }

        $registered = 0;
        $not_registered = 0;
        $belum_registrasi = 0;
        $data_registrasi = [];

        $user_id = $token ? $user['id'] : $user->id;

        $host = env('LOS_API_HOST');
        $headers = [
            'token' => env('LOS_API_TOKEN')
        ];

        if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
            // Registrasi
            $registered = DB::table('asuransi')
                            ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                            ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                            ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                            ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                            ->select('asuransi.id')
                            ->where('asuransi.registered', 1)
                            ->where('k.is_asuransi', true)
                            ->where('k.kode_cabang', $user_cabang)
                            ->count();

            $not_registered = DB::table('asuransi')
                            ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                            ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                            ->select('asuransi.id')
                            ->where('asuransi.registered', 0)
                            ->where('k.is_asuransi', true)
                            ->where('k.kode_cabang', $user_cabang)
                            ->count();
            // retrieve from api
            $apiURL = "$host/v1/get-list-pengajuan";

            try {
                $response = Http::timeout(60)
                                ->withHeaders($headers)
                                ->withOptions(['verify' => false])
                                ->get($apiURL, [
                                    'kode_cabang' => $user_cabang,
                                    'user' => 'all',
                                ]);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);

                if ($responseBody) {
                    if(array_key_exists('data', $responseBody)) {
                        if (array_key_exists('data', $responseBody['data'])) {
                            $data = $responseBody['data']['data'];

                            foreach ($data as $key => $value) {
                                $nip = $value['karyawan']['nip'];
                                $nama = $value['karyawan']['nama'];
                                // retrieve jenis_asuransi
                                $jenis_asuransi = DB::table('mst_jenis_asuransi')
                                                    ->select('id', 'jenis')
                                                    ->where('jenis_kredit', $value['skema_kredit'])
                                                    ->orderBy('jenis')
                                                    ->get();
                                $jml_asuransi = $jenis_asuransi ? count($jenis_asuransi) : 0;
                                $jml_diproses = 0;

                                foreach ($jenis_asuransi as $key2 => $value2) {
                                    // retrieve asuransi data
                                    $asuransi = DB::table('asuransi')
                                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                                ->leftJoin('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                                ->select(
                                                    'asuransi.registered',
                                                )
                                                ->where('asuransi.jenis_asuransi_id', $value2->id);

                                    $asuransi = $asuransi->groupBy('no_pk')
                                                        ->orderBy('no_aplikasi')
                                                        ->first();
                                    if (!$asuransi) {
                                        $belum_registrasi++;
                                    }
                                    $value2->asuransi = $asuransi;
                                }
                                $data[$key]['jenis_asuransi'] = $jenis_asuransi;
                                $d = [
                                    'nip' => $nip,
                                    'nama' => $nama,
                                    'jml_asuransi' => $jml_asuransi,
                                    'jml_diproses' => $jml_diproses,
                                ];

                                array_push($data_registrasi, $d);
                            }
                        }
                    }
                } else {
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // return $e->getMessage();
            }
        } else {
            // Penyelia & staf
            // retrieve from api
            $apiURL = "$host/v1/get-list-pengajuan";

            try {
                $response = Http::timeout(60)
                                ->withHeaders($headers)
                                ->withOptions(['verify' => false])
                                ->get($apiURL, [
                                    'kode_cabang' => $user_cabang,
                                    'user' => $user_id,
                                ]);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);

                if ($responseBody) {
                    if(array_key_exists('data', $responseBody)) {
                        if (array_key_exists('data', $responseBody['data'])) {
                            $data = $responseBody['data']['data'];

                            foreach ($data as $key => $value) {
                                $nip = $value['karyawan']['nip'];
                                $nama = $value['karyawan']['nama'];
                                // retrieve jenis_asuransi
                                $jenis_asuransi = DB::table('mst_jenis_asuransi')
                                                    ->select('id', 'jenis')
                                                    ->where('jenis_kredit', $value['skema_kredit'])
                                                    ->orderBy('jenis')
                                                    ->get();
                                $jml_asuransi = $jenis_asuransi ? count($jenis_asuransi) : 0;
                                $jml_diproses = 0;

                                foreach ($jenis_asuransi as $key2 => $value2) {
                                    // retrieve asuransi data
                                    $asuransi = DB::table('asuransi')
                                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                                ->leftJoin('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                                ->select(
                                                    'asuransi.registered',
                                                )
                                                ->where('asuransi.jenis_asuransi_id', $value2->id);

                                    $asuransi = $asuransi->groupBy('no_pk')
                                                        ->orderBy('no_aplikasi')
                                                        ->first();
                                    if (!$asuransi) {
                                        $belum_registrasi++;
                                    }
                                    else {
                                        $registered += $asuransi->registered ? 1 : 0;
                                        $not_registered += $asuransi->registered ? 0 : 1;
                                    }
                                    $value2->asuransi = $asuransi;
                                }
                                $data[$key]['jenis_asuransi'] = $jenis_asuransi;
                                $d = [
                                    'nip' => $nip,
                                    'nama' => $nama,
                                    'jml_asuransi' => $jml_asuransi,
                                    'jml_diproses' => $jml_diproses,
                                ];

                                array_push($data_registrasi, $d);
                            }
                        }
                    }
                } else {
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // return $e->getMessage();
            }
        }
        $this->param['registered'] = $registered;
        $this->param['not_registered'] = $not_registered;
        $this->param['belum_registrasi'] = $belum_registrasi;

        $result = $this->group_by("nip", $data_registrasi);
        $finalResult = [];
        if ($result) {
            foreach ($result as $key => $value) {
                $jml_asuransi = 0;
                $jml_diproses = 0;
                for ($i=0; $i < count($value); $i++) {
                    $jml_asuransi += $value[$i]['jml_asuransi'];
                    $jml_diproses += $value[$i]['jml_diproses'];
                };
                $final_d = [
                    'nip' => $key,
                    'nama' => $value[0]['nama'],
                    'jml_asuransi' => $jml_asuransi,
                    'jml_diproses' => $jml_diproses,
                ];

                array_push($finalResult, $final_d);
            }
        }
        $this->param['result'] = $finalResult;

        return view('pages.detail.registrasi', $this->param);
    }

    public function detailPembayaranPremi(){
        $token = \Session::get(config('global.user_token_session'));
        $user = $token ? $this->getLoginSession() : Auth::user();
        $user_cabang = $token ? $user['kode_cabang'] : $user->kode_cabang;
        $role_id = \Session::get(config('global.role_id_session'));
        $role = '';
        if ($user) {
            if (is_array($user)) {
                $role = $user['role'];
            }
        } else {
            $role = 'vendor';
        }

        $sudahBayar = 0;
        $belumBayar = 0;
        $data_pembayaran_premi = [];

        $user_id = $token ? $user['id'] : $user->id;

        $host = env('LOS_API_HOST');
        $headers = [
            'token' => env('LOS_API_TOKEN')
        ];

        if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {

            $dataSudahBayar = DB::table('asuransi')
            ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
            ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
            ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
            ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
            ->select('asuransi.id')
            ->where('asuransi.registered', 1)
            ->where('k.is_asuransi', true)
            ->where('asuransi.is_paid', true)
            ->where('k.kode_cabang', $user_cabang)
            ->count();

            $dataBelumBayar = DB::table('asuransi')
            ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
            ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
            ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
            ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
            ->select('asuransi.id')
            ->where('asuransi.registered', 1)
            ->where('k.is_asuransi', true)
            ->where('asuransi.is_paid', false)
            ->where('k.kode_cabang', $user_cabang)
            ->count();
            // retrieve from api
            $apiURL = "$host/v1/get-list-pengajuan";

            try {
                $response = Http::timeout(60)
                    ->withHeaders($headers)
                    ->withOptions(['verify' => false])
                    ->get($apiURL, [
                        'kode_cabang' => $user_cabang,
                        'user' => 'all',
                    ]);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);

                if ($responseBody) {
                    if (array_key_exists('data', $responseBody)) {
                        if (array_key_exists('data', $responseBody['data'])) {
                            $data = $responseBody['data']['data'];
                            $sudahBayar = 0;
                            $belumBayar = 0;
                            foreach ($data as $key => $value) {
                                $nip = $value['karyawan']['nip'];
                                $nama = $value['karyawan']['nama'];
                                // retrieve jenis_asuransi
                                $asuransi = DB::table('asuransi')
                                    ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                    ->leftJoin('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                    ->select(
                                        'asuransi.is_paid',
                                    );

                                $asuransi = $asuransi->groupBy('no_pk')
                                    ->orderBy('no_aplikasi')
                                    ->first();
                                if ($asuransi->is_paid == 0) {
                                    $belumBayar++;
                                }
                                else {
                                    $sudahBayar++;
                                }
                                $d = [
                                    'nip' => $nip,
                                    'nama' => $nama,
                                    'belum_bayar' => $belumBayar,
                                    'sudah_bayar' => $sudahBayar,
                                ];

                                array_push($data_pembayaran_premi, $d);
                            }
                        }
                    }
                } else {
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // return $e->getMessage();
            }
        } else {
            // Penyelia & staf
            // retrieve from api
            $apiURL = "$host/v1/get-list-pengajuan";

            try {
                $response = Http::timeout(60)
                    ->withHeaders($headers)
                    ->withOptions(['verify' => false])
                    ->get($apiURL, [
                        'kode_cabang' => $user_cabang,
                        'user' => $user_id,
                    ]);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);

                if ($responseBody) {
                    if (array_key_exists('data', $responseBody)) {
                        if (array_key_exists('data', $responseBody['data'])) {
                            $data = $responseBody['data']['data'];

                            $sudahBayar = 0;
                            $belumBayar = 0;
                            foreach ($data as $key => $value) {
                                $nip = $value['karyawan']['nip'];
                                $nama = $value['karyawan']['nama'];
                                // retrieve jenis_asuransi
                                $asuransi = DB::table('asuransi')
                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                ->leftJoin('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                    ->select(
                                        'asuransi.is_paid',
                                        )->where('k.pengajuan_id', $value['id']);
                                $asuransi = $asuransi->orderBy('no_aplikasi')
                                ->get();
                                        // return $asuransi;


                                    $totalAsurnasi = count($asuransi);

                                    foreach ($asuransi as $key => $value) {
                                        if ($value->is_paid == 1) {
                                            $sudahBayar ++;
                                        }else{
                                            $belumBayar ++;
                                            $sudahBayar--;
                                        }
                                        // return ['sudah bayar'=>$sudahBayar, 'belum bayar'=>$belumBayar];
                                        $d = [
                                            'nip' => $nip,
                                            'nama' => $nama,
                                            'total' => $totalAsurnasi,
                                            'jmlh_sudah_bayar' => $sudahBayar,
                                            'jmlh_belum_bayar' => $belumBayar,
                                        ];
                                        array_push($data_pembayaran_premi, $d);
                                    }
                                }
                                // return $data_pembayaran_premi;
                        }
                    }
                } else {
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // return $e->getMessage();
            }
        }



        $result = $this->group_by("nip", $data_pembayaran_premi);
        // return $result;
        $finalResult = [];
        if ($result) {
            $jmlh_belum_bayar = 0;
            $jmlh_sudah_bayar = 0;
            foreach ($result as $key => $value) {
                // return ['totalsudah' => $jmlh_sudah_bayar += $value['jmlh_sudah_bayar']];
                for ($i=0; $i < count($value); $i++) {
                    $jmlh_belum_bayar += $value[$i]['jmlh_belum_bayar'];
                    $jmlh_sudah_bayar += $value[$i]['jmlh_sudah_bayar'];
                    // $total == $value[$i]['total'];
                };
                $final_d = [
                    'nip' => $key,
                    'nama' => $value[0]['nama'],
                    'jmlh_sudah_bayar' => $jmlh_sudah_bayar,
                    'jmlh_belum_bayar' => $jmlh_belum_bayar,
                    'total' => $value[0]['total'],
                ];

                array_push($finalResult, $final_d);
            }
        }
        // return $finalResult;
        $this->param['result'] = $finalResult;

        return view('pages.detail.pembayaran-premi', $this->param);
    }

    function group_by($key, $data) {
        $result = array();

        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }

        return $result;
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
        $tanggalAwal = date('Y') . '-' . date('m') . '-01';
        $hari_ini = now();
        $tAwal = date("Y-m-d", strtotime(Request()->tAwal));
        $tAkhir = date("Y-m-d", strtotime(Request()->tAkhir));
        $host = env('LOS_API_HOST');
        $headers = [
            'token' => env('LOS_API_TOKEN')
        ];

        $apiCabang = $host . '/kkb/get-cabang/';
        $api_req = Http::timeout(6)->withHeaders($headers)->get($apiCabang);
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
                    ->whereBetween('kkb.updated_at', [$tanggalAwal, $hari_ini])
                    ->when($tAwal && $tAkhir, function ($query) use ($tAwal, $tAkhir) {
                        return $query->whereBetween('kkb.updated_at', [$tAwal, $tAkhir]);
                    })
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
