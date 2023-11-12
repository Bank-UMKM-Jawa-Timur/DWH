<?php

namespace App\Http\Controllers\Report\Asuransi;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class PengajuanKlaimController extends Controller
{
    
    public function index(Request $request){
        // dd($request->all());
        ini_set('max_execution_time', 120);
        try{
            $allCabang = $this->getAllCabang();
            if($allCabang['status'] == 'berhasil'){
                $allCabang = $allCabang['data'];
            }
    
            $dataKlaim = null;
            $staf = null;
            $allStatus = [
                'waiting approval',
                'approved',
                'revition',
                'canceled'
            ];
            if($request->has('dari') && $request->has('sampai')){
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
                $status = 'all';
                if ($request->has('status'))
                    $status = $request->status;
                $page_length = $request->page_length ? $request->page_length : 5;
                $tAwal = date('Y-m-d', strtotime($request->get('dari')));
                $tAkhir = date('Y-m-d', strtotime($request->get('sampai')));
    
                $dataKlaim = DB::table('asuransi')
                    ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                    ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                    ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                    ->join('pengajuan_klaim as pk', 'asuransi.id', 'pk.asuransi_id')
                    ->select('k.pengajuan_id', 'asuransi.no_aplikasi', 'asuransi.no_rek', 'pk.status', 'asuransi.no_polis', 'asuransi.no_pk')
                    ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                    ->when($kode_cabang, function ($query) use ($kode_cabang) {
                        if ($kode_cabang != 'all')
                            return $query->where('k.kode_cabang', $kode_cabang);
                    })
                    // ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                    ->when($user_id, function ($query) use ($user_id) {
                        if ($user_id != 'all')
                            return $query->where('asuransi.user_id', $user_id);
                    })
                    ->when($status, function($query) use ($status){
                        if($status != 'all')
                            return $query->where('pk.status', $status);
                    })
                    ->paginate($page_length);
    
                $host = env('LOS_API_HOST');
                $headers = [
                    'token' => env('LOS_API_TOKEN')
                ];
                foreach($dataKlaim as $i => $item){
                    $apiPengajuan = $host . '/v1/get-list-pengajuan-by-id/' . $item->pengajuan_id;
                    $api_req = Http::timeout(6)->withHeaders($headers)->get($apiPengajuan);
                    $response = json_decode($api_req->getBody(), true);
                    $pengajuan = null;
                    if ($response) {
                        if (array_key_exists('status', $response)) {
                            if ($response['status'] == 'success') {
                                $pengajuan = $response['data'];
                                $item->dataNasabah = $pengajuan;
                            }
                        }
                    }
                }
            }
            $data = [
                'cabang' => $allCabang,
                'staf' => $staf,
                'pengajuan_klaim' => $dataKlaim,
                'allStatus' => $allStatus
            ];

            return view('pages.report.asuransi.pengajuan_klaim.index', $data);

        } catch(Exception $e){
            Alert::error('Gagal', $e->getMessage());
            return back();
        } catch(QueryException $e){
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }

    public function pembatalan(Request $request) {
        ini_set('max_execution_time', 120);
        try {
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

                // if ($request->has('cabang')) {
                //     $kode_cabang = $request->cabang;
                //     if ($request->cabang != 'all') {
                //         $staf = $this->getStafByCabang($request->cabang);
                //         if ($request->nip != 'all') {
                //             if ($request->status != 'all') {
                //                 $total_canceled = DB::table('asuransi')
                //                         ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                //                         ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                //                         ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                //                         ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                //                         ->select('asuransi.no_aplikasi')
                //                         ->where('asuransi.status', 'canceled')
                //                         ->where('k.is_asuransi', true)
                //                         ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                //                         ->where('k.kode_cabang', $kode_cabang)
                //                         ->when($user_id, function ($query) use ($user_id) {
                //                             if ($user_id != 'all')
                //                                 $query->where('asuransi.user_id', $user_id);
                //                         })
                //                         ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                //                         ->where('asuransi.status', 'canceled')
                //                         ->where('k.is_asuransi', true)
                //                         ->where('k.kode_cabang', $kode_cabang)
                //                         ->when($user_id, function ($query) use ($user_id) {
                //                             if ($user_id != 'all')
                //                                 $query->where('asuransi.user_id', $user_id);
                //                         })
                //                         ->count();

                //                 $index_cabang = array_search($kode_cabang, $arrCabang);
                //                 $selected_cabang = [$arrCabang[$index_cabang]];
                //                 array_push($arrCanceled, $total_canceled);
                //             }else{
                //                 foreach ($staf as $key => $value) {
                //                     $staf_name = $value['detail']['nama'];
                //                     $total_canceled = DB::table('asuransi')
                //                             ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                //                             ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                //                             ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                //                             ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                //                             ->select('asuransi.no_aplikasi')
                //                             ->where('asuransi.status', 'canceled')
                //                             ->where('k.is_asuransi', true)
                //                             ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                //                             ->where('k.kode_cabang', $kode_cabang)
                //                             ->where('asuransi.user_id', $value['id'])
                //                             ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                //                             ->where('asuransi.status', 'canceled')
                //                             ->where('k.is_asuransi', true)
                //                             ->where('k.kode_cabang', $kode_cabang)
                //                             ->where('asuransi.user_id', $value['id'])
                //                             ->count();
    
                //                     array_push($arrStaf, $staf_name);
                //                     array_push($arrCanceled, $total_canceled);
                //                 }
                //             }
                //         }else{
                //             $total_canceled = DB::table('asuransi')
                //                         ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                //                         ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                //                         ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                //                         ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                //                         ->select('asuransi.no_aplikasi')
                //                         ->where('asuransi.status', 'canceled')
                //                         ->where('k.is_asuransi', true)
                //                         ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                //                         ->where('k.kode_cabang', $kode_cabang)
                //                         ->when($user_id, function ($query) use ($user_id) {
                //                             if ($user_id != 'all')
                //                                 $query->where('asuransi.user_id', $user_id);
                //                         })
                //                         ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                //                         ->where('asuransi.status', 'canceled')
                //                         ->where('k.is_asuransi', true)
                //                         ->where('k.kode_cabang', $kode_cabang)
                //                         ->when($user_id, function ($query) use ($user_id) {
                //                             if ($user_id != 'all')
                //                                 $query->where('asuransi.user_id', $user_id);
                //                         })
                //                         ->count();

                //                 $index_cabang = array_search($kode_cabang, $arrCabang);
                //                 $selected_cabang = [$arrCabang[$index_cabang]];
                //                 array_push($arrCanceled, $total_canceled);
                //         }
                //     }else{
                //         foreach ($allCabang as $key => $value) {
                //             $total_canceled = DB::table('asuransi')
                //                                 ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                //                                 ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                //                                 ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                //                                 ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                //                                 ->select('asuransi.no_aplikasi')
                //                                 ->where('asuransi.status', 'canceled')
                //                                 ->where('k.is_asuransi', true)
                //                                 ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                //                                 ->when($user_id, function ($query) use ($user_id) {
                //                                     if ($user_id != 'all') {
                //                                         $query->where('asuransi.user_id', $user_id);
                //                                     }
                //                                 })
                //                                 ->where('k.kode_cabang', $value['kode_cabang'])
                //                                 ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                //                                 ->where('asuransi.status', 'canceled')
                //                                 ->where('k.is_asuransi', true)
                //                                 ->when($user_id, function ($query) use ($user_id) {
                //                                     if ($user_id != 'all') {
                //                                         $query->where('asuransi.user_id', $user_id);
                //                                     }
                //                                 })
                //                                 ->where('k.kode_cabang', $value['kode_cabang'])
                //                                 ->count();

                //             array_push($arrCanceled, $total_canceled);
                //         }
                //     }
                // }
                $staf = $this->getStafByCabang($request->cabang);
                dd($request->cabang);
            }

            $data = [
                'cabang' => $allCabang,
                'staf' => $staf,
                // 'asuransi' => $asuransi,
                // 'selectedCabang' => $selected_cabang,
                // 'dataCanceled' => $arrCanceled,
                // 'dataCabang' => $arrCabang,
                // 'dataStaf' => $arrStaf,
            ];

            return view('pages.report.asuransi.pengajuan_klaim.pembatalan_klaim', $data);
        } catch (\Throwable $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        }
    }
}
