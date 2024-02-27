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
use Exception;

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
            $tanggalAwal = date('Y') . '-' . date('m') . '-01';
            $hari_ini = now();
            $tAwal = date("Y-m-d", strtotime($request->tAwal));
            $tAkhir = date("Y-m-d", strtotime($request->tAkhir));
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

            $dataAsuransi = DB::table('asuransi')->where('status', 'sended')->where('registered', 1)->get();

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

            // $dataAsuransi = DB::table('asuransi')->get();
            $belumBayar = 0;
            $sudahBayar = 0;
            $sudahBayar = DB::table('asuransi')
                                    ->select('id')
                                    ->where('registered', 1)
                                    ->where('status', 'sended')
                                    ->where('is_paid', 1)
                                    ->count();
            $belumBayar = DB::table('asuransi')
                                    ->select('id')
                                    ->where('registered', 1)
                                    ->where('status', 'sended')
                                    ->where('is_paid', 0)
                                    ->count();

            $this->param['sudahBayar'] = $sudahBayar;
            $this->param['belumBayar'] = $belumBayar;

            $user_cabang = $token ? $user['kode_cabang'] : $user->kode_cabang;

            $apiCabang = $this->losHost . '/kkb/get-cabang';
            $this->losHeaders['Authorization'] = "Bearer $token";
            $api_req = Http::timeout(20)->withHeaders($this->losHeaders)->get($apiCabang);

            $responseCabang = json_decode($api_req->getBody(), true);

            $this->param['dataCabang'] = $responseCabang;
            if (!$token)
                $user_id = 0;

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
                // Penyelia & staf
                $total_sudah_klaim = DB::table('asuransi')
                                        ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                        ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                        ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                        ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                        ->select('asuransi.id')
                                        ->where('asuransi.registered', 1)
                                        ->where('asuransi.is_paid', 1)
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
                                        ->where('asuransi.is_paid', 1)
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


                    // retrieve from api
                    $data_registrasi = [];
                    $apiURL = "$this->losHost/v1/get-list-pengajuan";
                    try {
                        $response = Http::timeout(60)
                                        ->withHeaders($this->losHeaders)
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
                                                        // ->join('pengajuan_klaim AS klaim', 'klaim.asuransi_id', 'asuransi.id')
                                                        ->select(
                                                            'asuransi.registered','asuransi.*'
                                                        )
                                                        ->where('asuransi.jenis_asuransi_id', $value2->id);

                                            $asuransi = $asuransi->groupBy('no_pk')
                                                                ->orderBy('no_aplikasi')
                                                                ->first();
                                            if (!$asuransi) {
                                                // $belum_klaim++;
                                                $belum_registrasi++;
                                            }
                                            else {
                                                $jml_diproses += $jml_diproses;
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

                    $this->param['role'] = $role;
                    if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
                        $result = $request->has('id_penyelia') ? $this->group_by("debitur", $data_registrasi) : $this->group_by("nip", $data_registrasi);
                    }
                    else {
                        if ($role == 'Staf Analis Kredit') {
                            $result = $this->group_by("debitur", $data_registrasi);
                        }
                        else {
                            $result = $this->group_by("nip", $data_registrasi);
                        }
                    }

                    $finalResult = [];
                    foreach ($result as $key => $value) {
                        $jml_asuransi = 0;
                        $jml_diproses = 0;
                        for ($i=0; $i < count($value); $i++) {
                            $jml_asuransi += $value[$i]['jml_asuransi'];
                            $jml_diproses += $value[$i]['jml_diproses'];
                        };

                        if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
                            $nip = $request->has('id_penyelia') ? $value[0]['nip']  : $key;
                        }
                        else {
                            if ($role == 'Staf Analis Kredit') {
                                $nip = $value[0]['nip'];
                            }
                            else {
                                $nip = $key;
                            }
                        }

                        $total_sudah_klaim += $jml_diproses;
                        $total_belum_klaim += $jml_asuransi - $total_sudah_klaim;

                    }


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

    public function detailRegistrasi(Request $request) {
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
        $this->losHeaders['Authorization'] = "Bearer $token";

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
            $apiURL = "$this->losHost/v1/get-list-pengajuan";

            try {
                $user = $request->has('id_penyelia') ? $request->id_penyelia : 'all';
                $response = Http::timeout(60)
                                ->withHeaders($this->losHeaders)
                                ->withOptions(['verify' => false])
                                ->get($apiURL, [
                                    'kode_cabang' => $user_cabang,
                                    'user' => $user,
                                ]);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);

                if ($responseBody) {
                    if(array_key_exists('data', $responseBody)) {
                        if (array_key_exists('data', $responseBody['data'])) {
                            $data = $responseBody['data']['data'];

                            foreach ($data as $key => $value) {
                                $id = $value['id_penyelia'];
                                $debitur = $value['nama'];
                                $nip = $request->has('id_penyelia') ? $value['staf']['nip'] : $value['karyawan']['nip'];
                                $nama = $request->has('id_penyelia') ? $value['staf']['nama'] : $value['karyawan']['nama'];
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
                                    } else {
                                        $jml_diproses++;
                                    }
                                    $value2->asuransi = $asuransi;
                                }
                                $data[$key]['jenis_asuransi'] = $jenis_asuransi;
                                $d = [
                                    'id' => $id,
                                    'nip' => $nip,
                                    'nama' => $nama,
                                    'debitur' => $debitur,
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
            $apiURL = "$this->losHost/v1/get-list-pengajuan";

            try {
                $response = Http::timeout(60)
                                ->withHeaders($this->losHeaders)
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
                                $id = $value['id_penyelia'];
                                $nip = $request->has('id_penyelia') ? $value['staf']['nip'] : $value['karyawan']['nip'];
                                $nama = $role == 'Penyelia Kredit' || $request->has('id_penyelia') ? $value['staf']['nama'] : $value['karyawan']['nama'];
                                $debitur = $value['nama'];
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
                                        $jml_diproses++;
                                        $registered += $asuransi->registered ? 1 : 0;
                                        $not_registered += $asuransi->registered ? 0 : 1;
                                    }
                                    $value2->asuransi = $asuransi;
                                }
                                $data[$key]['jenis_asuransi'] = $jenis_asuransi;
                                $d = [
                                    'id' => $id,
                                    'nip' => $nip,
                                    'nama' => $nama,
                                    'debitur' => $debitur,
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
        $this->param['role'] = $role;

        if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
            $result = $request->has('staf') ? $this->group_by("debitur", $data_registrasi) : $this->group_by("nip", $data_registrasi);
        }
        else {
            if ($role == 'Staf Analis Kredit') {
                $result = $this->group_by("debitur", $data_registrasi);
            }
            else {
                $result = $request->has('staf') ?  $this->group_by("debitur", $data_registrasi) : $this->group_by("nip", $data_registrasi);
            }
        }

        $finalResult = [];
        if ($result) {
            foreach ($result as $key => $value) {
                $jml_asuransi = 0;
                $jml_diproses = 0;
                for ($i=0; $i < count($value); $i++) {
                    $jml_asuransi += $value[$i]['jml_asuransi'];
                    $jml_diproses += $value[$i]['jml_diproses'];
                };

                if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
                    $nip = $request->has('id_penyelia') ? $value[0]['nip']  : $key;
                }
                else {
                    if ($role == 'Staf Analis Kredit') {
                        $nip = $value[0]['nip'];
                    }
                    else {
                        $nip = $key;
                    }
                }
                $final_d = [
                    'id' => $value[0]['id'],
                    'nip' => $nip,
                    'nama' => $value[0]['nama'],
                    'debitur' => $value[0]['debitur'],
                    'jml_asuransi' => $jml_asuransi,
                    'jml_diproses' => $jml_diproses,
                ];

                array_push($finalResult, $final_d);
            }
        }

        $this->param['result'] = $finalResult;

        return view('pages.detail.registrasi', $this->param);
    }

    public function detailPengajuanKlaim(Request $request) {
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
        $belum_klaim = 0;
        $data_registrasi = [];

        $user_id = $token ? $user['id'] : $user->id;

        $total_sudah_klaim = 0;
        $total_belum_klaim = 0;

        $this->losHeaders['Authorization'] = "Bearer $token";

        if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
            $total_sudah_klaim = DB::table('asuransi')
                                    ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                    ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                    ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                    ->select('asuransi.id')
                                    ->where('asuransi.registered', 1)
                                    ->where('asuransi.is_paid', 1)
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
                                ->where('asuransi.is_paid', 1)
                                ->where('k.is_asuransi', true)
                                ->where('k.kode_cabang', $user_cabang)
                                ->whereRaw('asuransi.id NOT IN (SELECT asuransi_id FROM pengajuan_klaim)')
                                ->count();
            // retrieve from api
            $apiURL = "$this->losHost/v1/get-list-pengajuan";

            try {
                $user = $request->has('id_penyelia') ? $request->id_penyelia : 'all';
                $response = Http::timeout(60)
                                ->withHeaders($this->losHeaders)
                                ->withOptions(['verify' => false])
                                ->get($apiURL, [
                                    'kode_cabang' => $user_cabang,
                                    'user' => $user,
                                ]);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);

                if ($responseBody) {
                    if(array_key_exists('data', $responseBody)) {
                        if (array_key_exists('data', $responseBody['data'])) {
                            $data = $responseBody['data']['data'];

                            foreach ($data as $key => $value) {
                                $id = $value['id_penyelia'];
                                $debitur = $value['nama'];
                                $nip = $request->has('id_penyelia') ? $value['staf']['nip'] : $value['karyawan']['nip'];
                                $nama = $request->has('id_penyelia') ? $value['staf']['nama'] : $value['karyawan']['nama'];
                                // retrieve jenis_asuransi
                                $jenis_asuransi = DB::table('mst_jenis_asuransi')
                                                    ->select('id', 'jenis')
                                                    ->where('jenis_kredit', $value['skema_kredit'])
                                                    ->orderBy('jenis')
                                                    ->get();
                                $jml_asuransi = $jenis_asuransi ? count($jenis_asuransi) : 0;
                                $jml_diproses = 0;
                                $klaim_status = [];

                                foreach ($jenis_asuransi as $key2 => $value2) {
                                    // retrieve asuransi data
                                    $asuransi = DB::table('asuransi')
                                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                                ->leftJoin('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                                ->join('pengajuan_klaim AS klaim', 'klaim.asuransi_id', 'asuransi.id')
                                                ->select(
                                                    'klaim.stat_klaim',
                                                )
                                                ->where('asuransi.jenis_asuransi_id', $value2->id);

                                    $asuransi = $asuransi->groupBy('no_pk')
                                                        ->orderBy('no_aplikasi')
                                                        ->first();
                                    if (!$asuransi) {
                                        $belum_klaim++;
                                    } else {
                                        array_push($klaim_status, $asuransi->stat_klaim);
                                        $jml_diproses++;
                                    }
                                    $value2->asuransi = $asuransi;
                                }
                                $data[$key]['jenis_asuransi'] = $jenis_asuransi;
                                $d = [
                                    'id' => $id,
                                    'nip' => $nip,
                                    'nama' => $nama,
                                    'debitur' => $debitur,
                                    'jml_asuransi' => $jml_asuransi,
                                    'jml_diproses' => $jml_diproses,
                                    'klaim' => $klaim_status,
                                ];

                                if ($request->has('id_penyelia') && $asuransi) {
                                    array_push($data_registrasi, $d);
                                }
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
            $total_sudah_klaim = DB::table('asuransi')
                                    ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                    ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                    ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                    ->select('asuransi.id')
                                    ->where('asuransi.registered', 1)
                                    ->where('asuransi.is_paid', 1)
                                    ->where('k.is_asuransi', 1)
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
                                ->where('asuransi.is_paid', 1)
                                ->where('k.is_asuransi', 1)
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
            // retrieve from api
            // dd($total_belum_klaim);
            $apiURL = "$this->losHost/v1/get-list-pengajuan";

            try {
                $response = Http::timeout(60)
                                ->withHeaders($this->losHeaders)
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
                                $id = $value['id_penyelia'];
                                $nip = $request->has('id_penyelia') ? $value['staf']['nip'] : $value['karyawan']['nip'];
                                $nama = $role == 'Penyelia Kredit' || $request->has('id_penyelia') ? $value['staf']['nama'] : $value['karyawan']['nama'];
                                $debitur = $value['nama'];
                                // retrieve jenis_asuransi
                                $jenis_asuransi = DB::table('mst_jenis_asuransi')
                                                    ->select('id', 'jenis')
                                                    ->where('jenis_kredit', $value['skema_kredit'])
                                                    ->orderBy('jenis')
                                                    ->get();
                                $jml_asuransi = $jenis_asuransi ? count($jenis_asuransi) : 0;
                                $jml_diproses = 0;
                                $klaim_status = [];

                                foreach ($jenis_asuransi as $key2 => $value2) {
                                    // retrieve asuransi data
                                    $asuransi = DB::table('asuransi')
                                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                                ->leftJoin('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                                ->join('pengajuan_klaim AS klaim', 'klaim.asuransi_id', 'asuransi.id')
                                                ->select(
                                                    'klaim.stat_klaim','asuransi.*'
                                                )
                                                ->where('asuransi.jenis_asuransi_id', $value2->id);

                                    $asuransi = $asuransi->groupBy('no_pk')
                                                        ->orderBy('no_aplikasi')
                                                        ->first();

                                    if (!$asuransi) {
                                        $belum_klaim++;
                                    }
                                    else {
                                        array_push($klaim_status, $asuransi->stat_klaim);
                                        $jml_diproses++;
                                        $registered += $asuransi->registered ? 1 : 0;
                                        $not_registered += $asuransi->registered ? 0 : 1;
                                    }
                                    $value2->asuransi = $asuransi;
                                }
                                $data[$key]['jenis_asuransi'] = $jenis_asuransi;
                                $d = [
                                    'id' => $id,
                                    'nip' => $nip,
                                    'nama' => $nama,
                                    'debitur' => $debitur,
                                    'jml_asuransi' => $jml_asuransi,
                                    'jml_diproses' => $jml_diproses,
                                    'klaim' => $klaim_status,
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

        $this->param['role'] = $role;
        if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
            $result = $request->has('id_penyelia') ? $this->group_by("debitur", $data_registrasi) : $this->group_by("nip", $data_registrasi);
        }
        else {
            if ($role == 'Staf Analis Kredit') {
                $result = $this->group_by("debitur", $data_registrasi);
            }
            else {
                $result = $this->group_by("nip", $data_registrasi);
            }
        }

        $finalResult = [];
        if ($result) {
            foreach ($result as $key => $value) {
                $jml_asuransi = 0;
                $jml_diproses = 0;
                for ($i=0; $i < count($value); $i++) {
                    $jml_asuransi += $value[$i]['jml_asuransi'];
                    $jml_diproses += $value[$i]['jml_diproses'];
                };

                if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
                    $nip = $request->has('id_penyelia') ? $value[0]['nip']  : $key;
                }
                else {
                    if ($role == 'Staf Analis Kredit') {
                        $nip = $value[0]['nip'];
                    }
                    else {
                        $nip = $key;
                    }
                }

                $final_d = [
                    'id' => $value[0]['id'],
                    'nip' => $nip,
                    'nama' => $value[0]['nama'],
                    'debitur' => $value[0]['debitur'],
                    'jml_asuransi' => $jml_asuransi,
                    'jml_diproses' => $jml_diproses,
                ];

                array_push($finalResult, $final_d);

                $total_sudah_klaim += $jml_diproses - $jml_diproses;
                $total_belum_klaim += $jml_asuransi - $jml_diproses;
            }


            $this->param['total_sudah_klaim'] = $total_sudah_klaim;
            $this->param['total_belum_klaim'] = $total_belum_klaim;
        }


        // dd($finalResult);
        $this->param['result'] = $finalResult;
        // return $finalResult;
        return view('pages.detail.pengajuan-klaim', $this->param);
    }

    public function detailPembayaranPremi(Request $request) {
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

        $belumBayar = 0;
        $sudahBayar = 0;
        $data_registrasi = [];

        $user_id = $token ? $user['id'] : $user->id;
        $this->losHeaders['Authorization'] = "Bearer $token";

        if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
            $sudahBayar = DB::table('asuransi')
                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                ->select('id')
                                ->where('registered', 1)
                                ->where('status', 'sended')
                                ->where('is_paid', 1)
                                ->where('asuransi.registered', 1)
                                ->where('k.is_asuransi', true)
                                ->where('k.kode_cabang', $user_cabang)
                                ->count();
            $belumBayar = DB::table('asuransi')
                            ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                            ->select('id')
                            ->where('registered', 1)
                            ->where('status', 'sended')
                            ->where('is_paid', 0)
                            ->where('asuransi.registered', 1)
                            ->where('k.is_asuransi', true)
                            ->where('k.kode_cabang', $user_cabang)
                            ->count();

            // retrieve from api
            $apiURL = "$this->losHost/v1/get-list-pengajuan";

            try {
                $user = $request->has('id_penyelia') ? $request->id_penyelia : 'all';
                $response = Http::timeout(60)
                                ->withHeaders($this->losHeaders)
                                ->withOptions(['verify' => false])
                                ->get($apiURL, [
                                    'kode_cabang' => $user_cabang,
                                    'user' => $user,
                                ]);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);

                if ($responseBody) {
                    if(array_key_exists('data', $responseBody)) {
                        if (array_key_exists('data', $responseBody['data'])) {
                            $data = $responseBody['data']['data'];

                            foreach ($data as $key => $value) {
                                $id = $value['id_penyelia'];
                                $debitur = $value['nama'];
                                $nip = $request->has('id_penyelia') ? $value['staf']['nip'] : $value['karyawan']['nip'];
                                $nama = $request->has('id_penyelia') ? $value['staf']['nama'] : $value['karyawan']['nama'];
                                // retrieve jenis_asuransi
                                $jenis_asuransi = DB::table('mst_jenis_asuransi')
                                                    ->select('id', 'jenis')
                                                    ->where('jenis_kredit', $value['skema_kredit'])
                                                    ->orderBy('jenis')
                                                    ->get();

                                foreach ($jenis_asuransi as $key2 => $value2) {
                                    // retrieve asuransi data
                                    $pembayaran = DB::table('asuransi')
                                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                                ->leftJoin('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                                ->leftJoin('pembayaran_premi_detail AS pem', 'pem.asuransi_id', 'asuransi.id')
                                                ->select(
                                                    'asuransi.id',
                                                    'asuransi.is_paid',
                                                )
                                                ->where('asuransi.status', 'sended')
                                                ->where('asuransi.jenis_asuransi_id', $value2->id);

                                    $pembayaran = $pembayaran->groupBy('asuransi.no_pk')
                                                        ->orderBy('asuransi.no_aplikasi')
                                                        ->first();
                                    $value2->pembayaran = $pembayaran;
                                    if ($pembayaran) {
                                        $d = [
                                            'id' => $id,
                                            'asuransi_id' => $pembayaran ? $pembayaran->id : null,
                                            'nip' => $nip,
                                            'nama' => $nama,
                                            'debitur' => $debitur,
                                            'is_paid' => $pembayaran ? $pembayaran->is_paid : null,
                                        ];

                                        array_push($data_registrasi, $d);
                                    }
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
        } else {
            // Penyelia & staf
            $sudahBayar = DB::table('asuransi')
                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                ->select('id')
                                ->where('registered', 1)
                                ->where('status', 'sended')
                                ->where('is_paid', 1)
                                ->where('asuransi.registered', 1)
                                ->where('k.is_asuransi', true)
                                ->where('k.kode_cabang', $user_cabang)
                                ->count();
            $belumBayar = DB::table('asuransi')
                            ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                            ->select('id')
                            ->where('registered', 1)
                            ->where('status', 'sended')
                            ->where('is_paid', 0)
                            ->where('asuransi.registered', 1)
                            ->where('k.is_asuransi', true)
                            ->where('k.kode_cabang', $user_cabang)
                            ->count();
            // retrieve from api
            $apiURL = "$this->losHost/v1/get-list-pengajuan";

            try {
                $response = Http::timeout(60)
                                ->withHeaders($this->losHeaders)
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
                                $id = $value['id_penyelia'];
                                $nip = $request->has('id_penyelia') ? $value['staf']['nip'] : $value['karyawan']['nip'];
                                $nama = $role == 'Penyelia Kredit' || $request->has('id_penyelia') ? $value['staf']['nama'] : $value['karyawan']['nama'];
                                $debitur = $value['nama'];
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
                                    $pembayaran = DB::table('asuransi')
                                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                                ->leftJoin('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                                ->leftJoin('pembayaran_premi_detail AS pem', 'pem.asuransi_id', 'asuransi.id')
                                                ->select(
                                                    'asuransi.id',
                                                    'asuransi.is_paid',
                                                )
                                                ->where('asuransi.status', 'sended')
                                                ->where('asuransi.jenis_asuransi_id', $value2->id);

                                    $pembayaran = $pembayaran->groupBy('asuransi.no_pk')
                                                        ->orderBy('asuransi.no_aplikasi')
                                                        ->first();
                                    $value2->pembayaran = $pembayaran;
                                    if ($pembayaran) {
                                        $d = [
                                            'id' => $id,
                                            'asuransi_id' => $pembayaran ? $pembayaran->id : null,
                                            'nip' => $nip,
                                            'nama' => $nama,
                                            'debitur' => $debitur,
                                            'is_paid' => $pembayaran ? $pembayaran->is_paid : null,
                                        ];

                                        array_push($data_registrasi, $d);
                                    }
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
        }

        $this->param['sudah_bayar'] = $sudahBayar;
        $this->param['belum_bayar'] = $belumBayar;
        $this->param['role'] = $role;

        if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
            $result = $request->has('staf') ? $this->group_by("debitur", $data_registrasi) : $this->group_by("nip", $data_registrasi);
        }
        else {
            if ($role == 'Staf Analis Kredit') {
                $result = $this->group_by("debitur", $data_registrasi);
            }
            else {
                $result = $request->has('staf') ?  $this->group_by("debitur", $data_registrasi) : $this->group_by("nip", $data_registrasi);
            }
        }

        $finalResult = [];
        if ($result) {
            foreach ($result as $key => $value) {
                $dibayar = 0;
                $belum_dibayar = 0;
                for ($i=0; $i < count($value); $i++) {
                    if ($value[$i]['is_paid'] == 1) {
                        $dibayar++;
                    }
                    else {
                        $belum_dibayar++;
                    }
                };

                if ($role == 'Pincab' || $role == 'PBP' || $role == 'PBO') {
                    $nip = $request->has('id_penyelia') ? $value[0]['nip']  : $key;
                }
                else {
                    if ($role == 'Staf Analis Kredit') {
                        $nip = $value[0]['nip'];
                    }
                    else {
                        $nip = $key;
                    }
                }
                $final_d = [
                    'id' => $value[0]['id'],
                    'nip' => $nip,
                    'nama' => $value[0]['nama'],
                    'debitur' => $value[0]['debitur'],
                    'dibayar' => $dibayar,
                    'belum_dibayar' => $belum_dibayar,
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
