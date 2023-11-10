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
            $registered = 0;
            $not_register = 0;
            $belum_registrasi = 0;
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

                $registered = DB::table('asuransi')
                            ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                            ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                            ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                            ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                            ->select('asuransi.id')
                            ->where('asuransi.registered', 1)
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
                            ->where('asuransi.registered', 1)
                            ->where('k.is_asuransi', true)
                            ->when($kode_cabang, function ($query) use ($kode_cabang) {
                                if ($kode_cabang != 'all')
                                    $query->where('k.kode_cabang', $kode_cabang);
                            })
                            ->when($user_id, function ($query) use ($user_id) {
                                if ($user_id != 'all')
                                    $query->where('asuransi.user_id', $user_id);
                            })
                            ->count();

                $not_register = DB::table('asuransi')
                            ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                            ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                            ->select('asuransi.id')
                            ->where('asuransi.registered', 0)
                            ->where('k.is_asuransi', true)
                            ->whereBetween('asuransi.created_at', [$tAwal, $tAkhir])
                            ->when($kode_cabang, function ($query) use ($kode_cabang) {
                                if ($kode_cabang != 'all')
                                    $query->where('k.kode_cabang', $kode_cabang);
                            })
                            ->when($user_id, function ($query) use ($user_id) {
                                if ($user_id != 'all')
                                    $query->where('asuransi.user_id', $user_id);
                            })
                            ->count();

                // retrieve from api
                $host = env('LOS_API_HOST');
                $headers = [
                    'token' => env('LOS_API_TOKEN')
                ];
                $apiURL = "$host/v1/get-list-pengajuan";
                $belum_registrasi = 0;

                try {
                    $response = Http::timeout(60)
                                    ->withHeaders($headers)
                                    ->withOptions(['verify' => false])
                                    ->get($apiURL, [
                                        'kode_cabang' => $kode_cabang,
                                        'user' => $user_id,
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
            }

            $data = [
                'cabang' => $allCabang,
                'staf' => $staf,
                'registered' => $registered,
                'not_register' => $not_register,
                'belum_registrasi' => $belum_registrasi,
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
            $selected_cabang = null;
            $arrCabang = [];
            $arrStaf = [];
            $arrCanceled = [];
            if ($request->has('dari') && $request->has('sampai')) {
                $tAwal = date('Y-m-d', strtotime($request->get('dari')));
                $tAkhir = date('Y-m-d', strtotime($request->get('sampai')));
                $user_id = 'all';
                if ($request->has('nip')) {
                    $user_id = $request->nip;
                }

                $kode_cabang = 'all';
                foreach ($allCabang as $key => $value) {
                    array_push($arrCabang, $value['cabang']);
                }
                if ($request->has('cabang')) {
                    $kode_cabang = $request->cabang;
                    if ($request->cabang != 'all') {
                        $staf = $this->getStafByCabang($request->cabang);
                        if ($request->nip != 'all') {
                            $total_canceled = DB::table('asuransi')
                                        ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                        ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                        ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                        ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                        ->select('asuransi.no_aplikasi')
                                        ->where('asuransi.status', 'canceled')
                                        ->where('k.is_asuransi', true)
                                        ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                                        ->where('k.kode_cabang', $kode_cabang)
                                        ->when($user_id, function ($query) use ($user_id) {
                                            if ($user_id != 'all')
                                                $query->where('asuransi.user_id', $user_id);
                                        })
                                        ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                                        ->where('asuransi.status', 'canceled')
                                        ->where('k.is_asuransi', true)
                                        ->where('k.kode_cabang', $kode_cabang)
                                        ->when($user_id, function ($query) use ($user_id) {
                                            if ($user_id != 'all')
                                                $query->where('asuransi.user_id', $user_id);
                                        })
                                        ->count();

                            $index_cabang = array_search($kode_cabang, $arrCabang);
                            $selected_cabang = [$arrCabang[$index_cabang]];
                            array_push($arrCanceled, $total_canceled);
                        }
                        else {
                            foreach ($staf as $key => $value) {
                                $staf_name = $value['detail']['nama'];
                                $total_canceled = DB::table('asuransi')
                                        ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                        ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                        ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                        ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                        ->select('asuransi.no_aplikasi')
                                        ->where('asuransi.status', 'canceled')
                                        ->where('k.is_asuransi', true)
                                        ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                                        ->where('k.kode_cabang', $kode_cabang)
                                        ->where('asuransi.user_id', $value['id'])
                                        ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                                        ->where('asuransi.status', 'canceled')
                                        ->where('k.is_asuransi', true)
                                        ->where('k.kode_cabang', $kode_cabang)
                                        ->where('asuransi.user_id', $value['id'])
                                        ->count();

                                array_push($arrStaf, $staf_name);
                                array_push($arrCanceled, $total_canceled);
                            }
                        }

                    } else {
                        foreach ($allCabang as $key => $value) {
                            $total_canceled = DB::table('asuransi')
                                                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                                                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                                                ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                                ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                                ->select('asuransi.no_aplikasi')
                                                ->where('asuransi.status', 'canceled')
                                                ->where('k.is_asuransi', true)
                                                ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                                                ->when($user_id, function ($query) use ($user_id) {
                                                    if ($user_id != 'all')
                                                        $query->where('asuransi.user_id', $user_id);
                                                })
                                                ->where('k.kode_cabang', $value['kode_cabang'])
                                                ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                                                ->where('asuransi.status', 'canceled')
                                                ->where('k.is_asuransi', true)
                                                ->when($user_id, function ($query) use ($user_id) {
                                                    if ($user_id != 'all')
                                                        $query->where('asuransi.user_id', $user_id);
                                                })
                                                ->where('k.kode_cabang', $value['kode_cabang'])
                                                ->count();

                            array_push($arrCanceled, $total_canceled);
                        }
                    }
                }
            }
            
            $data = [
                'cabang' => $allCabang,
                'staf' => $staf,
                'asuransi' => $asuransi,
                'selectedCabang' => $selected_cabang,
                'dataCanceled' => $arrCanceled,
                'dataCabang' => $arrCabang,
                'dataStaf' => $arrStaf,
            ];

            return view('pages.report.asuransi.registrasi.pembatalan', $data);
        } catch (Exception $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        }
    }

    public function pelaporanPelunasan(Request $request){
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
            $allCabang = $this->getAllCabang();

            if ($allCabang['status'] == 'berhasil') {
                $allCabang = $allCabang['data'];
            }

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

            $pelunasan = DB::table('pelaporan_pelunasan as pp')
                ->join('asuransi as a', 'a.id', 'pp.asuransi_id')
                ->join('kredits AS k', 'k.id', 'a.kredit_id')
                ->select(
                    'a.no_aplikasi',
                    'a.nama_debitur',
                    'a.no_rek',
                    'a.no_polis',
                    'pp.tanggal',
                    'pp.refund',
                    'pp.sisa_jkw'
                )
                ->whereBetween('pp.tanggal', [$tAwal, $tAkhir])
                ->where('a.done_by', '!=', null)
                ->when($kode_cabang, function ($query) use ($kode_cabang) {
                    if ($kode_cabang != 'all')
                        $query->where('k.kode_cabang', $kode_cabang);
                })
                ->when($user_id, function ($query) use ($user_id) {
                    if ($user_id != 'all')
                        $query->where('a.user_id', $user_id);
                });

            if ($request->has('q')) {
                $q = $request->get('q');
                $pelunasan = $pelunasan->when($q, function ($query) use ($q) {
                    $query->where('a.nama_debitur', 'LIKE', "%$q%")
                    ->where('a.done_at', true)
                    ->orWhere('a.no_aplikasi', 'LIKE', "%$q%")
                    ->where('a.done_at', true)
                    ->orWhere('a.no_polis', 'LIKE', "%$q%")
                    ->where('a.done_at', true)
                    ->orWhere('a.tgl_polis', 'LIKE', "%$q%")
                    ->where('a.done_at', true)
                    ->orWhere('a.tgl_rekam', 'LIKE', "%$q%")
                    ->where('a.done_at', true);
                });
            }

            if (is_numeric($page_length)) {
                $pelunasan = $pelunasan->orderBy('pp.tanggal', 'DESC')->paginate($page_length);
            } else {
                $pelunasan = $pelunasan->orderBy('pp.tanggal', 'DESC')->get();
            }

            $data = [
                'pelunasan' => $pelunasan,
                'staf' => $staf,
                'cabang' => $allCabang
            ];
            return view('pages.report.asuransi.pelunasan.pelunasan', $data);
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

            // if (empty($tAkhir)) {
            //     $tAkhir = $hari_ini;
            // } else {
                $tAkhir = date('Y-m-d', strtotime($tAkhir));
            // }

            $page_length = $request->page_length ? $request->page_length : 5;
            $data = DB::table('asuransi')
            ->select(
                'id',
                'no_aplikasi',
                'nama_debitur',
                'no_rek',
                'no_polis',
            );

            // $data = DB::table('log_activities')
            // ->where('is_asuransi', true)
            // ->whereBetween('created_at', [$tAwal, $tAkhir]);
            if ($request->has('q')) {
                $q = $request->get('q');
                $data = $data->where('no_aplikasi', 'LIKE', "%$q%")
                ->orWhere('nama_debitur','LIKE', "%$q%");
            }

            if (is_numeric($page_length)) {
                $data = $data->orderBy('created_at', 'DESC')->paginate($page_length);
            } else {
                $data = $data->orderBy('created_at', 'DESC')->get();
            }

            foreach ($data as $key => $value) {
               $value->activity = DB::table('log_activities')
               ->where('asuransi_id', $value->id)
               ->where('is_asuransi', true)
                // ->whereBetween('created_at', [$tAwal, $tAkhir])
                ->get();
            }


            // return $data;
            return view('pages.report.asuransi.registrasi.log-data', $data);
        } catch (Exception $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        }
    }
}
