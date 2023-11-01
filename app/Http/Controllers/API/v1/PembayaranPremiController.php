<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Asuransi;
use App\Models\PembayaranPremi;
use Illuminate\Http\Request;

class PembayaranPremiController extends Controller
{
    public function store(Request $request){
        $this->validate($request, [
            "nobukti_pembayaran" => "required",
            "tgl_bayar" => "required",
            "total_premi" => "required",
            "no_rek" => "required",
            "no_aplikasi" => "required",
            "no_pk" => "required",
            "periode_bayar" => "required",
            "total_periode" => "required",
        ],[
            'required' =>  'Atribut ini harus diisi.',
        ]);

        $asuransi = Asuransi::where('no_aplikasi', $request->no_aplikasi)->get();
        $cekNoApk = count($asuransi);
        
        if ($cekNoApk >= 1) {
            $request->no_polis;
    
            $createPremi = new PembayaranPremi();
            $createPremi->nobukti_pembayaran = $request->nobukti_pembayaran;
            $createPremi->tgl_bayar = $request->tgl_bayar;
            $createPremi->total_premi = $request->total_premi;
            $createPremi->no_rek = $request->no_rek;
            $createPremi->no_aplikasi = $request->no_aplikasi;
            $createPremi->no_pk = $request->no_pk;
            $createPremi->periode_bayar = $request->periode_bayar;
            $createPremi->total_periode = $request->total_periode;
            $createPremi->save();
    
            if ($createPremi) {
                return response()->json([
                    'nobukti_pembayaran' => $request->nobukti_pembayaran,
                    'tgl_bayar' => $request->tgl_bayar,
                    'total_premi' => (int)$request->total_premi,
                    'rincian_bayar' => array(
                        'premi'=> (int)$request->total_premi,
                        'no_rek'=> $request->no_rek,
                        'no_aplikasi'=> $request->no_aplikasi,
                        'no_pk'=> $request->no_pk,
                        'no_polis'=> $request->no_polis,
                        'periode_bayar'=> $request->periode_bayar,
                        'total_periode'=> $request->total_periode,
                    )
                ]);
            }else{
                return response()->json([
                    'message' => "Terjadi Kesalahan",
                ]);
            }
        }else{
            return response()->json([
                'status' => "03",
                'keterangan' => "No Aplikasi " . $request->no_aplikasi . "Tidak ditemukan",
            ]);
        }
    }
}
