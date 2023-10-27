<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Utils\UtilityController;
use App\Models\PengajuanKlaim;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;




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
        $data = DB::table('pengajuan_klaim');
        if ($request->has('search')) {
            $search = $request->get('search');
            $data = $data->where('no_rek', 'LIKE', "%$search%")
                ->orWhere('no_aplikasi', 'LIKE', "%$search%")
                ->orWhere('no_klaim', 'LIKE', "%$search%");
        }
        $data = $data->orderBy('no_aplikasi')->paginate($page_length);
        return view('pages.pengajuan-klaim.index', compact('data'));
    }
    public function create(Request $request)
    {
        $dataNoRek = DB::table('asuransi')->orderBy('no_aplikasi')->get();
        return view('pages.pengajuan-klaim.create', compact('dataNoRek'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_sp' => 'required',
            'no_sp3' => 'required',
            'tgl_sp3' => 'required',
            'tunggakan_pokok' => 'required',
            'tunggakan_bunga' => 'required',
            'tunggakan_denda' => 'required',
            'nilai_pengikatan' => 'required',
            'nilai_tuntutan_klaim' => 'required',
            'penyebab_klaim' => 'not_in:0',
        ], [
            'required' => ':attribute harus diisi.',
            'not_in' => ':attribute harus dipilih.',
        ], [
            'no_sp' => 'No Polis',
            'no_sp3' => 'No Surat Peringatan Ke 3',
            'tgl_sp3' => 'Tanggal Surat Peringatan ke 3',
            'tunggakan_pokok' => 'Tunggakan Pokok',
            'tunggakan_bunga' => 'Tunggakan Bunga',
            'tunggakan_denda' => 'Tunggakan Denda',
            'nilai_pengikatan' => 'Nilai Pengingkatan',
            'nilai_tuntutan_klaim' => 'Nilai Tuntutan Klaim',
            'penyebab_klaim' => 'Penyebab Klaim',
        ]);

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
                            # data Kurang lengkap
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
}
