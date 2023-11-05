<?php

namespace App\Http\Controllers\Report\Asuransi;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class RegistrasiController extends Controller
{
    public function registrasi(Request $request) {
        ini_set('max_execution_time', 120);
        try {
            $page_length = $request->page_length ? $request->page_length : 5;
            $allCabang = $this->getAllCabang();

            if ($allCabang['status'] == 'berhasil') {
                $allCabang = $allCabang['data'];
            }

            $asuransi = null;
            $staf = null;
            if ($request->has('dari') && $request->has('sampai')) {
                $kode_cabang = 'all';
                if ($request->has('cabang')) {
                    $kode_cabang = $request->cabang;
                    if ($request->cabang != 'all') {
                        $staf = $this->getStafByCabang($request->cabang);
                    }
                }
                $user_id = 'all';
                if ($request->has('nip')) {
                    $user_id = $request->nip;
                }

                $tAwal = date('Y-m-d', strtotime($request->get('dari')));
                $tAkhir = date('Y-m-d', strtotime($request->get('sampai')));

                $asuransi = DB::table('asuransi')
                            ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                            ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                            ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                            ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                            ->select(
                                'k.pengajuan_id',
                                'p.nama AS perusahaan',
                                'asuransi.*',
                                'mst_jenis_asuransi.jenis',
                                'k.kode_cabang',
                                'd.tarif',
                                'd.premi_disetor',
                                'd.handling_fee',
                            )
                            ->where('asuransi.status', 'sended')
                            ->where('k.is_asuransi', true)
                            ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                            ->when($kode_cabang, function ($query) use ($kode_cabang) {
                                if ($kode_cabang != 'all')
                                    $query->where('k.kode_cabang', $kode_cabang);
                            })
                            ->when($user_id, function ($query) use ($user_id) {
                                if ($user_id != 'all')
                                    $query->where('asuransi.user_id', $user_id);
                            })
                            ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                            ->where('asuransi.status', 'sended')
                            ->where('k.is_asuransi', true)
                            ->when($kode_cabang, function ($query) use ($kode_cabang) {
                                if ($kode_cabang != 'all')
                                    $query->where('k.kode_cabang', $kode_cabang);
                            })
                            ->when($user_id, function ($query) use ($user_id) {
                                if ($user_id != 'all')
                                    $query->where('asuransi.user_id', $user_id);
                            });

                if ($request->has('q')) {
                    $q = $request->get('q');
                    $asuransi = $asuransi->when($q, function($query) use ($q) {
                        $query->where('asuransi.nama_debitur', 'LIKE', "%$q%")
                                        ->where('asuransi.status', 'sended')
                                        ->orWhere('asuransi.no_aplikasi', 'LIKE', "%$q%")
                                        ->where('asuransi.status', 'sended')
                                        ->orWhere('asuransi.no_polis', 'LIKE', "%$q%")
                                        ->where('asuransi.status', 'sended')
                                        ->orWhere('asuransi.tgl_polis', 'LIKE', "%$q%")
                                        ->where('asuransi.status', 'sended')
                                        ->orWhere('asuransi.tgl_rekam', 'LIKE', "%$q%")
                                        ->where('asuransi.status', 'sended');
                    });
                }

                if (is_numeric($page_length)) {
                    $asuransi = $asuransi->orderBy('no_aplikasi')->paginate($page_length);
                } else {
                    $asuransi = $asuransi->orderBy('no_aplikasi')->get();
                }

                foreach ($asuransi as $key => $value) {
                    $host = env('LOS_API_HOST');
                    $headers = [
                        'token' => env('LOS_API_TOKEN')
                    ];

                    $apiPengajuan = $host . '/v1/get-list-pengajuan-by-id/' . $value->pengajuan_id;
                    $api_req = Http::timeout(6)->withHeaders($headers)->get($apiPengajuan);
                    $response = json_decode($api_req->getBody(), true);
                    $pengajuan = null;
                    if ($response) {
                        if (array_key_exists('status', $response)) {
                            if ($response['status'] == 'success') {
                                $pengajuan = $response['data'];
                            }
                        }
                    }
                    $value->pengajuan = $pengajuan;
                }
            }

            $data = [
                'cabang' => $allCabang,
                'staf' => $staf,
                'asuransi' => $asuransi,
            ];

            return view('pages.report.asuransi.registrasi.registrasi', $data);
        } catch (Exception $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        }
    }

    public function pembatalan(Request $request) {
        ini_set('max_execution_time', 120);
        try {
            $page_length = $request->page_length ? $request->page_length : 5;
            $allCabang = $this->getAllCabang();

            if ($allCabang['status'] == 'berhasil') {
                $allCabang = $allCabang['data'];
            }

            $asuransi = null;
            $staf = null;
            if ($request->has('dari') && $request->has('sampai')) {
                $kode_cabang = 'all';
                if ($request->has('cabang')) {
                    $kode_cabang = $request->cabang;
                    if ($request->cabang != 'all') {
                        $staf = $this->getStafByCabang($request->cabang);
                    }
                }
                $user_id = 'all';
                if ($request->has('nip')) {
                    $user_id = $request->nip;
                }

                $tAwal = date('Y-m-d', strtotime($request->get('dari')));
                $tAkhir = date('Y-m-d', strtotime($request->get('sampai')));


                $asuransi = DB::table('asuransi')
                            ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                            ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                            ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                            ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                            ->select(
                                'k.pengajuan_id',
                                'p.nama AS perusahaan',
                                'asuransi.*',
                                'mst_jenis_asuransi.jenis',
                                'k.kode_cabang',
                                'd.tarif',
                                'd.premi_disetor',
                                'd.handling_fee',
                            )
                            ->where('asuransi.status', 'canceled')
                            ->where('k.is_asuransi', true)
                            ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                            ->when($kode_cabang, function ($query) use ($kode_cabang) {
                                if ($kode_cabang != 'all')
                                    $query->where('k.kode_cabang', $kode_cabang);
                            })
                            ->when($user_id, function ($query) use ($user_id) {
                                if ($user_id != 'all')
                                    $query->where('asuransi.user_id', $user_id);
                            })
                            ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                            ->where('asuransi.status', 'canceled')
                            ->where('k.is_asuransi', true)
                            ->when($kode_cabang, function ($query) use ($kode_cabang) {
                                if ($kode_cabang != 'all')
                                    $query->where('k.kode_cabang', $kode_cabang);
                            })
                            ->when($user_id, function ($query) use ($user_id) {
                                if ($user_id != 'all')
                                    $query->where('asuransi.user_id', $user_id);
                            });
                if ($request->has('q')) {
                    $q = $request->get('q');
                    $asuransi = $asuransi->when($q, function($query) use ($q) {
                        $query->where('asuransi.nama_debitur', 'LIKE', "%$q%")
                            ->where('asuransi.status', 'canceled')
                            ->orWhere('asuransi.no_aplikasi', 'LIKE', "%$q%")
                            ->where('asuransi.status', 'canceled')
                            ->orWhere('asuransi.no_polis', 'LIKE', "%$q%")
                            ->where('asuransi.status', 'canceled')
                            ->orWhere('asuransi.tgl_polis', 'LIKE', "%$q%")
                            ->where('asuransi.status', 'canceled')
                            ->orWhere('asuransi.tgl_rekam', 'LIKE', "%$q%")
                            ->where('asuransi.status', 'canceled');
                    });
                }

                if (is_numeric($page_length)) {
                    $asuransi = $asuransi->orderBy('no_aplikasi')->paginate($page_length);
                } else {
                    $asuransi = $asuransi->orderBy('no_aplikasi')->get();
                }

                foreach ($asuransi as $key => $value) {
                    $host = env('LOS_API_HOST');
                    $headers = [
                        'token' => env('LOS_API_TOKEN')
                    ];

                    $apiPengajuan = $host . '/v1/get-list-pengajuan-by-id/' . $value->pengajuan_id;
                    $api_req = Http::timeout(6)->withHeaders($headers)->get($apiPengajuan);
                    $response = json_decode($api_req->getBody(), true);
                    $pengajuan = null;
                    if ($response) {
                        if (array_key_exists('status', $response)) {
                            if ($response['status'] == 'success') {
                                $pengajuan = $response['data'];
                            }
                        }
                    }
                    $value->pengajuan = $pengajuan;
                }
            }

            $data = [
                'cabang' => $allCabang,
                'staf' => $staf,
                'asuransi' => $asuransi,
            ];

            return view('pages.report.asuransi.registrasi.pembatalan', $data);
        } catch (Exception $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        }
    }


    public function logData(Request $request){
    ini_set('max_execution_time', 120);
        try {
            $tAwal = date('Y-m-d', strtotime($request->get('dari')));
            $tAkhir = $request->get('sampai');
            $hari_ini = now();

            if (empty($tAkhir)) {
                $tAkhir = $hari_ini;
            } else {
                $tAkhir = date('Y-m-d', strtotime($tAkhir));
            }

            $page_length = $request->page_length ? $request->page_length : 5;
            $data = DB::table('log_activities')->where('is_asuransi', 1)
            ->whereBetween('created_at', [$tAwal, $tAkhir]);
            if ($request->has('q')) {
                $q = $request->get('q');
                $data = $data->where('content', 'LIKE', "%$q%");
            }

            if (is_numeric($page_length)) {
                $data = $data->orderBy('created_at', 'DESC')->paginate($page_length);
            } else {
                $data = $data->orderBy('created_at', 'DESC')->get();
            }
            return view('pages.report.asuransi.registrasi.log-data', $data);
        } catch (Exception $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        }
    }
}
