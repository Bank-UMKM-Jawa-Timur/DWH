<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Utils\UtilityController;
use App\Models\PengajuanKlaim;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;




class PengajuanKlaimController extends Controller
{   

    private $logActivity;

    function __construct()
    {
        $this->logActivity = new LogActivitesController;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role_id = \Session::get(config('global.role_id_session'));
        $page_length = $request->page_length ? $request->page_length : 5;
        $data = DB::table('pengajuan_klaim AS p')->select(
                                    'p.id',
                                    'p.stat_klaim',
                                    'p.status',
                                    'a.no_aplikasi',
                                    'a.no_rek',
                                )
                                ->join('asuransi AS a', 'a.id', 'p.asuransi_id');
        if ($request->has('search')) {
            $search = $request->get('search');
            $data->where('a.no_rek', 'like', '%' . $search . '%')
                ->orWhere('p.status', 'like', '%' . $search . '%')
                ->orWhere('a.no_aplikasi', 'like', '%' . $search . '%');
            // $data->whereHas('asuransi', function ($q) use ($search) {
            //     $q->where('no_rek', 'like', '%' . $search . '%')
            //         ->orWhere('no_aplikasi', 'like', '%' . $search . '%')
            //         ->orWhere('no_klaim', 'like', '%' . $search . '%');
            // });
        }

        if (is_numeric($page_length)) {
            $data = $data->paginate($page_length);
        } else {
            $data = $data->get();
        }

        // return $data;

        return view('pages.pengajuan-klaim.index', compact('data', 'role_id'));
    }
    public function create(Request $request)
    {
        $role_id = \Session::get(config('global.role_id_session'));
        if ($role_id != 2) {
            Alert::warning('Peringatan', 'Anda tidak memiliki akses.');
            return back();
        }

        $dataNoRek = DB::table('asuransi')->orderBy('no_aplikasi')->get();
        return view('pages.pengajuan-klaim.create', compact('dataNoRek'));
    }

    public function store(Request $request)
    {
        ini_set('max_execution_time', 120);

        $role_id = \Session::get(config('global.role_id_session'));
        if ($role_id != 2) {
            Alert::warning('Peringatan', 'Anda tidak memiliki akses.');
            return back();
        }
        
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

        // return $request;

        $req = [
            'no_aplikasi' => $request->get('no_aplikasi'),
            'no_rekening' => $request->get('no_rekening'),
            'no_sp' => $request->get('no_sp'),
            'no_sp3' => $request->get('no_sp3'),
            'tgl_sp3' => date("Y-m-d", strtotime($request->get('tgl_sp3'))),
            'tunggakan_pokok' => UtilityController::clearCurrencyFormat($request->get('tunggakan_pokok')),
            'tunggakan_bunga' => UtilityController::clearCurrencyFormat($request->get('tunggakan_bunga')),
            'tunggakan_denda' => UtilityController::clearCurrencyFormat($request->get('tunggakan_denda')),
            'nilai_pengikatan' => UtilityController::clearCurrencyFormat($request->get('nilai_pengikatan')),
            'nilai_tuntutan_klaim' => UtilityController::clearCurrencyFormat($request->get('nilai_tuntutan_klaim')),
            'jenis_agunan' => $request->get('jenis_agunan'),
            'penyebab_klaim' => $request->get('penyebab_klaim'),
        ];
        DB::beginTransaction();
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
            $response = Http::timeout(60)->withHeaders($headers)->withOptions(['verify' => false])->post($url, $req);
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
                            $asuransi = DB::table('asuransi')
                                            ->select('id')
                                            ->where('no_aplikasi', $request->no_aplikasi)
                                            ->first();

                            $newPengajuanKlaim = new PengajuanKlaim();
                            $newPengajuanKlaim->asuransi_id = $asuransi->id;
                            $newPengajuanKlaim->stat_klaim = 1; // sedang diproses
                            $newPengajuanKlaim->status = 'onprogress';
                            $newPengajuanKlaim->save();

                            $this->logActivity->store('Pengguna ' . $request->name . ' menambahkan pengajuan klaim');

                            DB::commit();
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
            DB::rollBack();
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
            // "no_rekening" => $request->input('row_no_rek'),
            // "no_sp" => $request->input('row_no_sp'),
            // "tgl_klaim" => $request->input('row_tgl_klaim'),
            // "nilai_persetujuan" => $request->input('row_nilai_persetujuan'),
            // "keterangan" => $request->input('row_keterangan'),
            // "stat_klaim" => $request->input('row_status_klaim'),
            // "no_rekening_debet" => $request->input('row_no_rekening_debit'),
            // "no_klaim" => $request->input('row_no_klaim')
        ];
        
        DB::beginTransaction();
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

                    $this->logActivity->store('Pengguna ' . $request->name . ' cek status pengajuan klaim');

                    DB::commit();
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
            DB::rollBack();
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }

    public function pembatalanKlaim(Request $request){
        $req = [
            'no_aplikasi' => $request->no_aplikasi,
            'no_rekening' => $request->no_rekening,
            'no_sp' => $request->no_polis,
            'no_klaim' => $request->no_klaim
        ];

        DB::beginTransaction();
        try{
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $user_id = $token ? $user['id'] : $user->id;
            $headers = [
                "Accept" => "/",
                "x-api-key" => config('global.eka_lloyd_token'),
                "Content-Type" => "application/json",
                "Access-Control-Allow-Origin" => "*",
                "Access-Control-Allow-Methods" => "*"
            ];

            $host = config('global.eka_lloyd_host');
            $url = "$host/batal";
            $response = Http::withHeaders($headers)->withOptions(['verify' => false])->post($url, $req);
            $statusCode = $response->status();
            if ($statusCode == 200) {
                $responseBody = json_decode($response->getBody(), true);
                $code = $responseBody['code'];
                $message = '';
                switch($code){
                    case '01':
                        $message = $responseBody['keterangan'];
                        Alert::error('Gagal', $message);
                        return redirect()->route('pengajuan-klaim.index');
                        break;
                    case '02':
                        $message = $responseBody['keterangan'];
                        Alert::error('Gagal', $message);
                        return redirect()->route('pengajuan-klaim.index');
                        break;
                    case '03':
                        $message = $responseBody['keterangan'];
                        Alert::error('Gagal', $message);
                        return redirect()->route('pengajuan-klaim.index');
                        break;
                    case '05':
                        $message = $responseBody['keterangan'];

                        $pengajuanKlaim = PengajuanKlaim::find($request->id);
                        $pengajuanKlaim->canceled_at = date('Y-m-d');
                        $pengajuanKlaim->canceled_by = $user_id;
                        $pengajuanKlaim->save();

                        $this->logActivity->store('Pengguna ' . $request->name . ' melakukan pembatalan pengajuan klaim.');

                        DB::commit();
                        Alert::success('Berhasil', $message);
                        return redirect()->route('pengajuan-klaim.index');
                        break;
                    case '06':
                        $message = $responseBody['keterangan'];
                        Alert::error('Gagal', $message);
                        return redirect()->route('pengajuan-klaim.index');
                        break;
                    case '48':
                        $message = $responseBody['keterangan'];
                        Alert::error('Gagal', $message);
                        return redirect()->route('pengajuan-klaim.index');
                        break;
                    default :
                        Alert::error('Gagal', 'Terjadi kesalahan.');
                        return back();
                }
            }
            else {
                Alert::error('Gagal', 'Terjadi kesalahan');
                return back();
            }
        } catch(\Exception $e){
            DB::rollBack();
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }
}
