<?php

namespace App\Http\Controllers\Report\Asuransi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanKlaimController extends Controller
{
    public function index(Request $request){
        ini_set('max_execution_time', 120);
        $allCabang = $this->getAllCabang();
        if($allCabang['status'] == 'berhasil'){
            $allCabang = $allCabang['data'];
        }

        if($request->has('from') && $request->has('to')){
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

            $tAwal = date('Y-m-d', strtotime($request->get('from')));
            $tAkhir = date('Y-m-d', strtotime($request->get('to')));

            $data = DB::table('asuransi')
                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                ->join('pengajuan_klaim as pk', 'asuransi.id', 'pk.asuransi_id')
                ->select('k.pengajuan_id')
                ->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                ->when($kode_cabang, function ($query) use ($kode_cabang) {
                    if ($kode_cabang != 'all')
                        $query->where('k.kode_cabang', $kode_cabang);
                })
                ->when($user_id, function ($query) use ($user_id) {
                    if ($user_id != 'all')
                        $query->where('asuransi.user_id', $user_id);
                })
                ->when($status, function($query) use($status){
                    if($status != 'all')
                        $query->where('pk.status', $status);
                })
                ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                ->where('k.is_asuransi', true)
                ->when($kode_cabang, function ($query) use ($kode_cabang) {
                    if ($kode_cabang != 'all')
                        $query->where('k.kode_cabang', $kode_cabang);
                })
                ->when($user_id, function ($query) use ($user_id) {
                    if ($user_id != 'all')
                        $query->where('asuransi.user_id', $user_id);
                })
                ->get();
                
            dd($data);
        }
    }
}
