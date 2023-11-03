<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Utils\UtilityController;
use App\Models\Asuransi;
use App\Models\DetailAsuransi;
use App\Models\Kredit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RegistrasiController extends Controller
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
        try {
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();

            $user_id = $token ? $user['id'] : $user->id;
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

            $page_length = $request->page_length ? $request->page_length : 5;
            $data = DB::table('asuransi')
                ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                ->select('asuransi.*', 'mst_jenis_asuransi.jenis', 'k.kode_cabang')
                ->when(\Session::get(config('global.role_id_session')), function ($query) use ($request, $role, $user_id) {
                    if (strtolower($role) == 'administrator' || strtolower($role) == 'kredit umum' || strtolower($role) == 'pemasaran' || strtolower($role) == 'spi' || strtolower($role) == 'penyelia kredit') {
                        // non staf
                        $kode_cabang = \Session::get(config('global.user_token_session')) ? 
                        \Session::get(config('global.user_kode_cabang_session')) : Auth::user()->kode_cabang;
                        $query->where('k.kode_cabang', $kode_cabang);
                    }
                    else {
                        // staf
                        $query->where('asuransi.user_id', $user_id);
                    }
                });
            if ($request->has('q')) {
                $q = $request->get('q');
                $data = $data->where('nama_debitur', 'LIKE', "%$q%")
                            ->orWhere('no_aplikasi', 'LIKE', "%$q%")
                            ->orWhere('no_polis', 'LIKE', "%$q%")
                            ->orWhere('tgl_polis', 'LIKE', "%$q%")
                            ->orWhere('tgl_rekam', 'LIKE', "%$q%");
            }
            if ($request->has('tAwal') && $request->has('tAkhir')) {
                $tAwal = date('Y-m-d', strtotime($request->get('tAwal')));
                $tAkhir = date('Y-m-d', strtotime($request->get('tAkhir')));
                $status = $request->get('status');
                $data = $data->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                            ->where('status', $status)
                            ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                            ->where('status', $status);
            }

            $data = $data->groupBy('no_pk')
                ->orderBy('no_aplikasi')
                ->paginate($page_length);

            $dataDetail = [];
            foreach($data as $i => $item){
                $dataDetail[$i] = [];
                $detailAsuransi = DB::table('asuransi')
                    ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                    ->select('asuransi.*', 'mst_jenis_asuransi.jenis');
                if ($request->has('q')) {
                    $q = $request->get('q');
                    $detailAsuransi = $detailAsuransi
                                        ->where('asuransi.user_id', $user_id)
                                        ->where('nama_debitur', 'LIKE', "%$q%")
                                        ->where('no_pk', $item->no_pk)
                                        ->where('asuransi.id', '!=', $item->id)
                                        ->orWhere('no_aplikasi', 'LIKE', "%$q%")
                                        ->where('asuransi.user_id', $user_id)
                                        ->where('no_pk', $item->no_pk)
                                        ->where('asuransi.id', '!=', $item->id)
                                        ->orWhere('no_polis', 'LIKE', "%$q%")
                                        ->where('asuransi.user_id', $user_id)
                                        ->where('no_pk', $item->no_pk)
                                        ->where('asuransi.id', '!=', $item->id)
                                        ->orWhere('tgl_polis', 'LIKE', "%$q%")
                                        ->where('asuransi.user_id', $user_id)
                                        ->where('no_pk', $item->no_pk)
                                        ->where('asuransi.id', '!=', $item->id)
                                        ->orWhere('tgl_rekam', 'LIKE', "%$q%")
                                        ->where('asuransi.user_id', $user_id)
                                        ->where('no_pk', $item->no_pk)
                                        ->where('asuransi.id', '!=', $item->id);
                }
                if ($request->has('tAwal') && $request->has('tAkhir')) {
                    $tAwal = date('Y-m-d', strtotime($request->get('tAwal')));
                    $tAkhir = date('Y-m-d', strtotime($request->get('tAkhir')));
                    $status = $request->get('status');
                    $detailAsuransi = $detailAsuransi->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                                ->where('status', $status)
                                ->where('asuransi.user_id', $user_id)
                                ->where('no_pk', $item->no_pk)
                                ->where('asuransi.id', '!=', $item->id)
                                ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                                ->where('status', $status)
                                ->where('asuransi.user_id', $user_id)
                                ->where('no_pk', $item->no_pk)
                                ->where('asuransi.id', '!=', $item->id);
                }
                $detailAsuransi = $detailAsuransi->where('asuransi.no_pk', $item->no_pk)
                                                ->where('asuransi.id', '!=', $item->id)
                                                ->get();

                if(count($detailAsuransi) > 0){
                    foreach($detailAsuransi as $j => $itemDetail){
                        array_push($dataDetail[$i], $itemDetail);
                    }
                } else{
                    $dataDetail[$i] = [];
                }

                $item->detail = $dataDetail[$i];
            }

            return view('pages.asuransi-registrasi.index', compact('data', 'role_id', 'role'));
        } catch (\Exception $e) {
            dd($e);
            return back()->with('error', $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);
            return back()->with('error', 'Terjadi kesalahan pada database. '.$e->getMessage());
        }
    }

    public function getRatePremi(Request $request) {
        $status = '';
        $message = '';
        $data = null;

        try {
            $data = DB::table('mst_rate_premi')
                    ->select('id', 'masa_asuransi1', 'masa_asuransi2', 'rate')
                    ->where('jenis', $request->jenis)
                    ->where('masa_asuransi1', '>=', $request->masa_asuransi)
                    ->where('masa_asuransi2', '<=', $request->masa_asuransi)
                    ->OrWhere('jenis', $request->jenis)
                    ->where('masa_asuransi1', '<=', $request->masa_asuransi)
                    ->where('masa_asuransi2', '>=', $request->masa_asuransi)
                    ->first();

            if ($data) {
                $status = 'success';
                $message = 'Successfully retrieve data';
            }
            else {
                $status = 'succes';
                $message = 'Data is empty';
            }
        } catch (\Exception $e) {
            $status = 'failed';
            $message = $e->getMessage();
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = $e->getMessage();
        } finally {
            $res = [
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ];

            return response()->json($res);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $token = \Session::get(config('global.user_token_session'));
        $user = $token ? $this->getLoginSession() : Auth::user();

        $user_id = $token ? $user['id'] : $user->id;
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

        if ($role != 'Staf Analis Kredit') {
            Alert::warning('Peringatan', 'Anda tidak memiliki akses.');
            return back();
        }

        $token = \Session::get(config('global.user_token_session'));
        $user = $token ? $this->getLoginSession() : Auth::user();

        $user_id = $token ? $user['id'] : $user->id;
        $host = env('LOS_API_HOST');
        $headers = [
            'token' => env('LOS_API_TOKEN')
        ];

        $apiPengajuan = $host . '/v1/get-list-pengajuan/' . $user_id;
        $api_req = Http::timeout(6)->withHeaders($headers)->get($apiPengajuan);
        $response = json_decode($api_req->getBody(), true);
        $dataPengajuan = [];
        if (is_array($response)) {
            if (array_key_exists('data', $response))
                $dataPengajuan = $response['data'];
        }

        $dataAsuransi = DB::table('mst_jenis_asuransi')->get();

        return view('pages.asuransi-registrasi.create', compact('dataPengajuan', 'dataAsuransi'));
    }

    public function getJenisAsuransi(Request $request){
        $dataAsuransi = DB::table('mst_jenis_asuransi')->where('jenis_kredit', $request->jenis_kredit)->get();

        return response()->json([
            'data' => $dataAsuransi
        ]);
    }

    public function checkAsuransi(Request $request) {
        // Check asuransi already registered or not
        $status = '';
        $message = '';
        $jenis = null;
        try {
            $no_pk = $request->no_pk;
            $jenis_asuransi_option = explode('-', $request->jenis_asuransi);
            $jenis_asuransi_id = $jenis_asuransi_option[0];

            $asuransi = DB::table('asuransi AS a')
                            ->select('a.id', 'm.jenis')
                            ->join('mst_jenis_asuransi AS m', 'm.id', 'a.jenis_asuransi_id')
                            ->where('a.no_pk', $no_pk)
                            ->where('a.jenis_asuransi_id', $jenis_asuransi_id)
                            ->first();

            $status = "success";
            $message = $asuransi ? "Data ini telah terdaftar" : "Belum terdaftar";
            if ($asuransi)
                $jenis = $asuransi->jenis;
        } catch (\Exception $e) {
            $status = "failed";
            $message = $e->getMessage();
        } finally {
            $res = [
                'status' => $status,
                'message' => $message,
                'jenis' => $jenis,
            ];
            return response()->json($res);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', 120);
        $role_id = \Session::get(config('global.role_id_session'));
        if ($role_id != 2) {
            Alert::warning('Peringatan', 'Anda tidak memiliki akses.');
            return back();
        }

        DB::beginTransaction();
        try {
            $jenis_asuransi_option = explode('-', $request->jenis_asuransi);
            $req = [
                "no_aplikasi"=> $request->get('no_aplikasi'),
                "no_rekening"=> $request->get('no_rekening'),
                "jenis_asuransi"=> $jenis_asuransi_option[1],
                "tgl_pengajuan"=> date("Y-m-d", strtotime($request->get('tgl_pengajuan'))) ,
                "tgl_jatuhtempo"=> date("Y-m-d", strtotime($request->get('tgl_jatuhtempo'))) ,
                "kd_uker"=> $request->get('kode_cabang'),
                "nama_debitur"=> $request->get('nama_debitur'),
                "alamat_debitur"=> $request->get('alamat_debitur'),
                "tgl_lahir"=> date("Y-m-d", strtotime($request->get('tgl_lahir'))),
                "no_ktp"=> $request->get('no_ktp'),
                "no_pk"=> $request->get('no_pk'),
                "tgl_pk"=> $request->get('tgl_pk'),
                "plafon_kredit"=> UtilityController::clearCurrencyFormat($request->get('plafon_kredit')),
                "tgl_awal_kredit"=> date("Y-m-d",strtotime($request->get('tanggal_awal_kredit'))),
                "tgl_akhir_kredit"=> date("Y-m-d", strtotime($request->get('tanggal_akhir_kredit'))),
                "jml_bulan"=> $request->get('jumlah_bulan'),
                "jenis_pengajuan"=> $request->get('jenis_pengajuan'),
                "bade"=> $request->get('baki_debet'),
                "tunggakan"=> $request->get('tunggakan'),
                "kolektibilitas"=> $request->get('kolektibilitas'),
                "no_polis_sebelumnya"=> $request->get('no_polis_sebelumnya'),
                "jenis_pertanggungan"=> $request->get('jenis_pertanggungan'),
                "tipe_premi"=> $request->get('tipe_premi'),
                "premi"=> UtilityController::clearCurrencyFormat($request->get('premi')),
                "jenis_coverage"=> $request->get('jenis_coverage'),
                "tarif"=> $request->get('tarif'),
                "refund"=> UtilityController::clearCurrencyFormat($request->get('refund')),
                "kode_ls"=> $request->get('kode_ls'),
                // "jenis_kredit"=> $request->get('jenis_kredit'),
                "jenis_kredit"=> "01",
                "handling_fee"=> UtilityController::clearCurrencyFormat($request->get('handling_fee')),
                "premi_disetor"=> UtilityController::clearCurrencyFormat($request->get('premi_disetor')),
            ];

            $headers = [
                "Accept" => "application/json",
                "x-api-key" => config('global.eka_lloyd_token'),
                "Content-Type" => "application/json",
                "Connection" => "Keep-Alive"
            ];
            
            $host = config('global.eka_lloyd_host');
            $url = "$host/upload";
            $response = Http::timeout(60)->withHeaders($headers)
                            ->withOptions(['verify' => false])
                            ->post($url, $req);
            $statusCode = $response->status();

            if ($statusCode == 200) {
                $responseBody = json_decode($response->getBody(), true);
                if ($responseBody) {
                    $status = $responseBody['status'];

                    $message = '';
                    switch ($status) {
                        case '01':
                            # success
                            $polis = $responseBody['no_polis'];
                            $tgl_rekam = $responseBody['tgl_rekam'];
                            $tgl_polis = $responseBody['tgl_polis'];
                            $tgl_awal = date('Y-m-d', strtotime($request->get('tanggal_awal_kredit')));
                            $tgl_akhir = date('Y-m-d', strtotime($request->get('tanggal_akhir_kredit')));

                            $newKredit = new Kredit();
                            $newKredit->pengajuan_id = $request->pengajuan;
                            $newKredit->is_asuransi = 1;
                            $newKredit->kode_cabang = $request->kode_cabang;
                            $newKredit->created_at = now();
                            $newKredit->save();

                            $premi = UtilityController::clearCurrencyFormat($request->get('premi'));
                            $refund = UtilityController::clearCurrencyFormat($request->get('refund'));
                            $token = \Session::get(config('global.user_token_session'));
                            $user = $token ? $this->getLoginSession() : Auth::user();

                            $user_id = $token ? $user['id'] : $user->id;
                            $jenis_asuransi_id = $jenis_asuransi_option[0];
                            // insert asuransi
                            $newAsuransi = new Asuransi();
                            $newAsuransi->no_aplikasi = $request->no_aplikasi;
                            $newAsuransi->no_pk = $request->no_pk;
                            $newAsuransi->no_rek = $request->no_rekening;
                            $newAsuransi->premi = $premi;
                            $newAsuransi->refund = $refund;
                            $newAsuransi->kredit_id = $newKredit->id;
                            $newAsuransi->jenis_asuransi_id = $jenis_asuransi_id;
                            $newAsuransi->user_id = $user_id;
                            $newAsuransi->nama_debitur = $request->nama_debitur;
                            $newAsuransi->no_polis = $polis;
                            $newAsuransi->tgl_polis = $tgl_polis;
                            $newAsuransi->tgl_rekam = $tgl_rekam;
                            $newAsuransi->tanggal_awal = $tgl_awal;
                            $newAsuransi->tanggal_akhir = $tgl_akhir;
                            $newAsuransi->status = 'onprogress';
                            $newAsuransi->save();

                            // insert detail asuransi
                            $newDetail = new DetailAsuransi();
                            $newDetail->asuransi_id = $newAsuransi->id;
                            $newDetail->jenis_pengajuan = $request->jenis_pengajuan;
                            $newDetail->kolektibilitas = $request->kolektibilitas;
                            $newDetail->jenis_pertanggungan = $request->jenis_pertanggungan;
                            $newDetail->tipe_premi = $request->tipe_premi;
                            $newDetail->jenis_coverage = $request->jenis_coverage;
                            $newDetail->no_polis_sebelumnya = $request->no_polis_sebelumnya;
                            $newDetail->baki_debet = UtilityController::clearCurrencyFormat($request->baki_debet);
                            $newDetail->tunggakan = UtilityController::clearCurrencyFormat($request->tunggakan);
                            $newDetail->tarif = $request->tarif;
                            $newDetail->kode_layanan_syariah = $request->kode_ls;
                            $newDetail->handling_fee = UtilityController::clearCurrencyFormat($request->handling_fee);
                            $newDetail->premi_disetor = UtilityController::clearCurrencyFormat($request->premi_disetor);
                            $newDetail->save();

                            $this->logActivity->store('Pengguna ' . $request->name . ' tambah registrasi asuransi.');

                            $message = $responseBody['keterangan'];

                            DB::commit();
                            Alert::success('Berhasil', $message);
                            return redirect()->route('asuransi.registrasi.index');
                            break;
                        case '02':
                            # gagal
                            $message = $responseBody['keterangan'];
                            Alert::error('Gagal', $message);
                            return back();
                            break;
                        case '04':
                            # duplikasi data
                            $message = $responseBody['keterangan'];
                            Alert::error('Gagal', $message);
                            return back();
                            break;
                        case '05':
                            # data kurang lengkap
                            $message = $responseBody['keterangan'];
                            Alert::error('Gagal', $message);
                            return back();
                            break;
                        case '06':
                            # data kurang lengkap
                            $message = $responseBody['keterangan'];
                            Alert::error('Gagal', $message);
                            return back();
                            break;
                        case '08':
                            # hasil perhitungan premi x rate tidak sesuai
                            $message = $responseBody['keterangan'];
                            Alert::error('Gagal', $message);
                            return back();
                            break;

                        default:
                            Alert::error('Gagal', "Terjadi kesalahan. Kode status : $status");
                            return back();
                            break;
                    }
                }
            }
            else if ($statusCode == 504) {
                Alert::error('Gagal', "Terjadi kesalahan. Gateway time out");
                return back()->withInput();
            }
            else {
                Alert::error('Gagal', "Terjadi kesalahan. Kode status : $statusCode");
                return back()->withInput();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal', $e->getMessage());
            return back()->withInput();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            DB::rollBack();
            Alert::error('Gagal timeout', $e->getMessage());
            return back()->withInput();
        }
    }

    public function getUser($user_id) {
        $failed_response = [
            'status' => 'gagal',
            'message' => 'Gagal mengambil data'
        ];

        $host = env('LOS_API_HOST');
        $headers = [
            'token' => env('LOS_API_TOKEN')
        ];
        $apiURL = $host . "/kkb/get-data-users-by-id/$user_id";

        try {
            $response = Http::timeout(3)
                            ->withHeaders($headers)
                            ->withOptions(['verify' => false])
                            ->get($apiURL);
            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody) {
                if (array_key_exists('id', $responseBody)) {
                    return $responseBody;
                }
            }
            return $failed_response;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $failed_response = [
                'status' => 'gagal',
                'message' => $e->getMessage(),
            ];
            return $failed_response;
        }
    }

    public function inquery(Request $request) {
        $token = \Session::get(config('global.user_token_session'));
        $user = $token ? $this->getLoginSession() : Auth::user();

        $user_id = $token ? $user['id'] : $user->id;
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

        if ($role != 'Staf Analis Kredit') {
            Alert::warning('Peringatan', 'Anda tidak memiliki akses.');
            return back();
        }

        try {
            $headers = [
                "Accept" => "/",
                "x-api-key" => config('global.eka_lloyd_token'),
                "Content-Type" => "application/json",
                "Access-Control-Allow-Origin" => "*",
                "Access-Control-Allow-Methods" => "*"
            ];
            $req = [
                "no_aplikasi" => $request->no_aplikasi
            ];
            $host = config('global.eka_lloyd_host');
            $url = "$host/query1";
            $response = Http::timeout(3)->withHeaders($headers)->withOptions(['verify' => false])->post($url, $req);
            // return $response;
            $statusCode = $response->status();
            if ($statusCode == 200) {
                $responseBody = json_decode($response->getBody(), true);
                if ($responseBody) {
                    $status = $responseBody['status'];
                    $message = '';
                    switch ($status) {
                        case '01':
                            # success
                            $message = $responseBody['keterangan'];

                            $this->logActivity->store('Pengguna ' . $request->name . ' melakukan inquery registrasi asuransi.');

                            Alert::success('Berhasil', $message);
                            return redirect()->route('asuransi.registrasi.index');
                            break;
                        case '02':
                            # success
                            $message = $responseBody['keterangan'];
                            Alert::warning('Peringatan', $message);
                            return redirect()->route('asuransi.registrasi.index');
                            break;
                        case '03':
                            # success
                            $message = $responseBody['keterangan'];
                            Alert::error('Gagal', $message);
                            return redirect()->route('asuransi.registrasi.index');
                            break;

                        default:
                            Alert::error('Gagal', 'Terjadi kesalahan');
                            return back();
                            break;
                    }
                }
            }
            else {
                Alert::error('Gagal', 'Terjadi kesalahan');
                return back();
            }
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }

    public function batal(Request $request){
        $token = \Session::get(config('global.user_token_session'));
        $user = $token ? $this->getLoginSession() : Auth::user();

        $user_id = $token ? $user['id'] : $user->id;
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

        if ($role != 'Staf Analis Kredit') {
            Alert::warning('Peringatan', 'Anda tidak memiliki akses.');
            return back();
        }

        try{
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $user_id = $token ? $user['id'] : $user->id;
            $asuransi = Asuransi::find($request->id);
            if ($asuransi) {
                if (!$asuransi->is_paid) {
                    if ($asuransi->status == 'onprogress') {
                        $headers = [
                            "Accept" => "/",
                            "x-api-key" => config('global.eka_lloyd_token'),
                            "Content-Type" => "application/json",
                            "Access-Control-Allow-Origin" => "*",
                            "Access-Control-Allow-Methods" => "*"
                        ];
                        $body = [
                            "no_aplikasi" => $asuransi->no_aplikasi,
                            "no_sp" => $asuransi->no_polis
                        ];
                        
                        $host = config('global.eka_lloyd_host');
                        $url = "$host/batal";
                        
                        $response = Http::timeout(60)->withHeaders($headers)->withOptions(['verify' => false])->post($url, $body);
    
                        $statusCode = $response->status();
    
                        if($statusCode == 200){
                            $responseBody = json_decode($response->getBody(), true);
                            if($responseBody){
                                if (array_key_exists('status', $responseBody)) {
                                    $status = $responseBody['status'];
                                    $keterangan = '';
    
                                    switch($status){
                                        case '00':
                                            $keterangan = $responseBody['keterangan'];
    
                                            $asuransi->status = 'canceled';
                                            $asuransi->canceled_at = date('Y-m-d');
                                            $asuransi->canceled_by = $user_id;
                                            $asuransi->save();
    
                                            $this->logActivity->store('Pengguna ' . $request->name . ' melakukan pembatalan registrasi asuransi.');
    
                                            Alert::success('Berhasil', $keterangan);
                                            break;
                                        case '44':
                                            $keterangan = $responseBody['keterangan'];
                                            Alert::error('Gagal', $keterangan);
                                            break;
                                        case '39':
                                            $keterangan = $responseBody['keterangan'];
                                            Alert::error('Gagal', $keterangan);
                                            break;
                                        default:
                                            Alert::error('Gagal', 'Terjadi kesalahan.');
                                    }
                                }
                                elseif (array_key_exists('message', $responseBody)) {
                                    Alert::error('Gagal', $responseBody['message']);
                                }
                                else {
                                    Alert::error('Gagal', 'Response tidak diketahui');
                                }
                            }
                        }
                        else {
                            Alert::warning('Peringatan', 'Server tidak ditemukan');
                        }
                    }
                    elseif ($asuransi->status == 'canceled') {
                        Alert::warning('Peringatan', 'Data ini sudah dibatalkan');
                    }
                    else {
                        Alert::warning('Peringatan', 'Data ini sudah terlunasi');
                    }
                }
                else {
                    Alert::warning('Peringatan', 'Proses tidak dapat dilakukan karena premi telah dibayarkan pada asuransi ini');
                }
            }
            else {
                Alert::warning('Peringatan', 'Data tidak ditemukan');
            }
        } catch(\Exception $e){
            Alert::error('Gagal', $e->getMessage());
        } finally {
            return redirect()->route('asuransi.registrasi.index');
        }
    }

    public function pelunasan(Request $request) {
        $token = \Session::get(config('global.user_token_session'));
        $user = $token ? $this->getLoginSession() : Auth::user();

        $user_id = $token ? $user['id'] : $user->id;
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

        if ($role != 'Staf Analis Kredit') {
            Alert::warning('Peringatan', 'Anda tidak memiliki akses.');
            return back();
        }

        try{
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $user_id = $token ? $user['id'] : $user->id;
            $asuransi = Asuransi::find($request->id);

            if ($asuransi) {
                if ($asuransi->status == 'onprogress') {
                    $headers = [
                        "Accept" => "/",
                        "x-api-key" => config('global.eka_lloyd_token'),
                        "Content-Type" => "application/json",
                        "Access-Control-Allow-Origin" => "*",
                        "Access-Control-Allow-Methods" => "*"
                    ];
                    $tgl_lunas = date('Y-m-d', strtotime($request->tgl_lunas));
                    $refund = UtilityController::clearCurrencyFormat($request->refund);
                    $jkw = str_replace(' bulan', '', $request->sisa_jangka_waktu);
                    $body = [
                        "no_aplikasi" => $request->no_aplikasi,
                        "no_rekening" => $request->no_rek,
                        "no_polis" => $request->no_polis,
                        "tgl_lunas" => $tgl_lunas,
                        "refund" => $refund,
                        "sisa_jkw" => $jkw
                    ];
                    
                    $host = config('global.eka_lloyd_host');
                    $url = "$host/lunas";

                    $response = Http::timeout(5)->withHeaders($headers)->withOptions(['verify' => false])->post($url, $body);
                    $statusCode = $response->status();

                    if($statusCode == 200){
                        $responseBody = json_decode($response->getBody(), true);
                        if($responseBody){
                            if (array_key_exists('status', $responseBody)) {
                                $status = $responseBody['status'];
                                $keterangan = '';

                                switch($status){
                                    case '00':
                                        $keterangan = $responseBody['keterangan'];

                                        $asuransi->status = 'done';
                                        $asuransi->done_at = date('Y-m-d');
                                        $asuransi->done_by = $user_id;
                                        $asuransi->save();

                                        $this->logActivity->store('Pengguna ' . $request->name . ' melakukan pelunasan registrasi asuransi.');

                                        Alert::success('Berhasil', $keterangan);
                                        return redirect()->route('asuransi.registrasi.index');
                                        break;
                                    case '01':
                                        $keterangan = $responseBody['keterangan'];
                                        Alert::error('Gagal', $keterangan);
                                        return redirect()->route('asuransi.registrasi.index');
                                        break;
                                    case '02':
                                        $keterangan = $responseBody['keterangan'];
                                        Alert::error('Gagal', $keterangan);
                                        return redirect()->route('asuransi.registrasi.index');
                                        break;
                                    case '04':
                                        $keterangan = $responseBody['keterangan'];
                                        Alert::error('Gagal', $keterangan);
                                        return redirect()->route('asuransi.registrasi.index');
                                        break;
                                    default:
                                        Alert::error('Gagal', 'Terjadi kesalahan.'.$status);
                                        return back();
                                }
                            }
                            elseif (array_key_exists('message', $responseBody)) {
                                Alert::error('Gagal', $responseBody['message']);
                                return back();
                            }
                            else {
                                Alert::error('Gagal', 'Response tidak diketahui');
                                return back();
                            }
                        }
                    }
                }
                elseif ($asuransi->status == 'canceled') {
                    Alert::warning('Peringatan', 'Data ini sudah dibatalkan');
                    return back();
                }
                else {
                    Alert::warning('Peringatan', 'Data ini sudah terlunasi');
                    return back();
                }
            }
            else {
                Alert::warning('Peringatan', 'Data tidak ditemukan');
                return back();
            }
        } catch(\Exception $e){
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }
}
