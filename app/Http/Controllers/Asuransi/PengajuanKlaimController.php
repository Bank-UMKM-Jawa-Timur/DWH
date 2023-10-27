<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Utils\UtilityController;
use App\Models\PengajuanKlaim;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Http;



class PengajuanKlaimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_length = $request->page_length ? $request->page_length : 5;
        $data = PengajuanKlaim::with('asuransi');
        if ($request->has('search')) {
            $search = $request->get('search');
            // $data = $data->where('no_rek', 'LIKE', "%$search%")
            //     ->orWhere('no_aplikasi', 'LIKE', "%$search%")
            //     ->orWhere('no_klaim', 'LIKE', "%$search%");
            $data->whereHas('asuransi', function ($q) use ($search) {
                $q->where('no_rek', 'like', '%' . $search . '%')
                    ->orWhere('no_aplikasi', 'like', '%' . $search . '%')
                    ->orWhere('no_klaim', 'like', '%' . $search . '%');
            });
        }

        if (is_numeric($page_length)) {
            $data = $data->paginate($page_length);
        } else {
            $data = $data->get();
        }
        // return $data;
        // $data = $data->orderBy('no_aplikasi')->paginate($page_length);
        return view('pages.pengajuan-klaim.index', compact('data'));
    }
    public function create(Request $request)
    {
        $dataNoRek = DB::table('asuransi')->orderBy('no_aplikasi')->get();
        return view('pages.pengajuan-klaim.create', compact('dataNoRek'));
    }

    public function store(Request $request)
    {
        $req = [
            'no_aplikasi' => $request->get('no_aplikasi'),
            'no_rekening' => $request->get('no_rekening'),
            'no_sp' => $request->get('no_sp'),
            'no_sp3' => $request->get('no_sp3'),
            'tgl_sp3' => $request->get('tgl_sp3'),
            'tunggakan_pokok' => UtilityController::clearCurrencyFormat($request->get('tunggakan_pokok')),
            'tunggakan_bunga' => UtilityController::clearCurrencyFormat($request->get('tunggakan_bunga')),
            'tunggakan_denda' => UtilityController::clearCurrencyFormat($request->get('tunggakan_denda')),
            'nilai_pengikatan' => UtilityController::clearCurrencyFormat($request->get('nilai_pengikatan')),
            'nilai_tuntutan_klaim' => UtilityController::clearCurrencyFormat($request->get('nilai_tuntutan_klaim')),
            'jenis_agunan' => $request->get('jenis_agunan'),
            'penyebab_klaim' => $request->get('penyebab_klaim'),
        ];
        // return $req;

        try {
            $headers = [
                "Accept" => "/",
                "x-api-key" => config('global.eka_lloyd_token'),
                "Content-Type" => "application/json",
                "Access-Control-Allow-Origin" => "*",
                "Access-Control-Allow-Methods" => "*"
            ];

            $host = config('global.eka_lloyd_host');
            $url = "$host/klaim";
            $response = Http::timeout(3)->withHeaders($headers)->withOptions(['verify' => false])->post($url, $req);
            $statusCode = $response->status();
            if ($statusCode == 200) {
                $responseBody = json_decode($response->getBody(), true);
                if ($responseBody) {
                    $status = $responseBody['status'];
                    $message = '';
                    switch ($status) {
                        case '00':
                            # success
                            $message = $responseBody['keterangan'];
                            $newPengajuanKlaim = new PengajuanKlaim();
                            $newPengajuanKlaim->no_klaim = $responseBody['no_klaim'];
                            $newPengajuanKlaim->no_aplikasi = $request->no_aplikasi;
                            $newPengajuanKlaim->no_rek = $request->no_rekening;
                            $newPengajuanKlaim->stat_klaim = 1;
                            $newPengajuanKlaim->status = 'onprogress';
                            $newPengajuanKlaim->save();

                            Alert::success('Berhasil', $message);
                            return redirect()->route('pengajuan-klaim.index');
                            break;
                        case '01':
                            # no aplikasi tidak ditemukan
                            $message = $responseBody['keterangan'];
                            Alert::error('Gagal', $message);
                            return back();
                            break;
                        case '02':
                            # gagal
                            $message = $responseBody['keterangan'];
                            Alert::error('Gagal', $message);
                            return back();
                            break;
                        case '03':
                            # no polis tidak ditemukan
                            $message = $responseBody['keterangan'];
                            Alert::error('Gagal', $message);
                            return back();
                            break;
                        default:
                            Alert::error('Gagal', 'Terjadi kesalahan');
                            return back();
                            break;
                    }
                }
            } else {
                Alert::error('Gagal', 'Terjadi kesalahan');
                return back();
            }
        } catch (\Exception $e) {
            Alert::error(
                'Gagal',
                $e->getMessage()
            );
            return back();
        }
    }

    public function cekStatus(Request $request){
        $req = [
            // "status" => $request->input(''),
            "no_aplikasi" => $request->input('row_no_aplikasi'),
            "no_rekening" => $request->input('row_no_rek'),
            "no_sp" => $request->input('row_no_sp'),
            "tgl_klaim" => $request->input('row_tgl_klaim'),
            "nilai_persetujuan" => $request->input('row_nilai_persetujuan'),
            "keterangan" => $request->input('row_keterangan'),
            "stat_klaim" => $request->input('row_status_klaim'),
            "no_rekening_debet" => $request->input('row_no_rekening_debit'),
            "no_klaim" => $request->input('row_no_klaim')
        ];

        try {
            $headers = [
                "Accept" => "/",
                "x-api-key" => config('global.eka_lloyd_token'),
                "Content-Type" => "application/json",
                "Access-Control-Allow-Origin" => "*",
                "Access-Control-Allow-Methods" => "*"
            ];

            $host = config('global.eka_lloyd_host');
            $url = "$host/query3";
            $response = Http::withHeaders($headers)->withOptions(['verify' => false])->post($url, $req);
            $statusCode = $response->status();
            if ($statusCode == 200) {
                $responseBody = json_decode($response->getBody(), true);
                $status = $responseBody['status'];
                $message = '';
                if ($status == "00") {
                    $message = $responseBody['keterangan'];
                    $nilai = $responseBody['nilai_premi'];
                    Alert::success('Berhasil', $message);
                    return back();
                }else{
                    $message = $responseBody['keterangan'];
                    Alert::error('Gagal', $message);
                    return back();
                }
            }
            else {
                Alert::error('Gagal', 'Terjadi kesalahan');
                return back();
            }
        } catch (\Throwable $e) {
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }
}
