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
}
