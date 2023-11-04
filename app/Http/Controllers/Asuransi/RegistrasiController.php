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
        ini_set('max_execution_time', 120);
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

            // retrieve from api
            $host = env('LOS_API_HOST');
            $headers = [
                'token' => env('LOS_API_TOKEN')
            ];
            $apiURL = "$host/v1/get-list-pengajuan/$user_id";

            try {
                $response = Http::timeout(60)->withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

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
                                                ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                                                ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                                                ->select(
                                                    'p.nama AS perusahaan',
                                                    'asuransi.*',
                                                    'mst_jenis_asuransi.jenis',
                                                    'k.kode_cabang',
                                                    'd.tarif',
                                                    'd.premi_disetor',
                                                    'd.handling_fee',
                                                )
                                                ->when($role_id, function ($query) use ($request, $role, $user_id, $token) {
                                                    if (strtolower($role) == 'administrator' || strtolower($role) == 'kredit umum' || strtolower($role) == 'pemasaran' || strtolower($role) == 'spi' || strtolower($role) == 'penyelia kredit') {
                                                        // non staf
                                                        $kode_cabang = $token ? \Session::get(config('global.user_kode_cabang_session')) : Auth::user()->kode_cabang;
                                                        $query->where('k.kode_cabang', $kode_cabang);
                                                    }
                                                    else {
                                                        // staf
                                                        $query->where('asuransi.user_id', $user_id);
                                                    }
                                                })
                                                ->where('asuransi.jenis_asuransi_id', $value2->id);

                                    if ($request->has('q')) {
                                        $q = $request->get('q');
                                        $asuransi = $asuransi->where('nama_debitur', 'LIKE', "%$q%")
                                                    ->where('asuransi.jenis_asuransi_id', $value2->id)
                                                    ->orWhere('no_aplikasi', 'LIKE', "%$q%")
                                                    ->where('asuransi.jenis_asuransi_id', $value2->id)
                                                    ->orWhere('no_polis', 'LIKE', "%$q%")
                                                    ->where('asuransi.jenis_asuransi_id', $value2->id)
                                                    ->orWhere('tgl_polis', 'LIKE', "%$q%")
                                                    ->where('asuransi.jenis_asuransi_id', $value2->id)
                                                    ->orWhere('tgl_rekam', 'LIKE', "%$q%")
                                                    ->where('asuransi.jenis_asuransi_id', $value2->id);
                                    }
                                    if ($request->has('tAwal') && $request->has('tAkhir')) {
                                        $tAwal = date('Y-m-d', strtotime($request->get('tAwal')));
                                        $tAkhir = date('Y-m-d', strtotime($request->get('tAkhir')));
                                        $status = $request->get('status');
                                        $asuransi = $asuransi->whereBetween('tgl_polis', [$tAwal, $tAkhir])
                                                    ->where('status', $status)
                                                    ->where('asuransi.jenis_asuransi_id', $value2->id)
                                                    ->orWhereBetween('tgl_rekam', [$tAwal, $tAkhir])
                                                    ->where('status', $status)
                                                    ->where('asuransi.jenis_asuransi_id', $value2->id);
                                    }

                                    $asuransi = $asuransi->groupBy('no_pk')
                                                        ->orderBy('no_aplikasi')
                                                        ->first();
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

            // return $data;
            return view('pages.asuransi-registrasi.index', compact('data', 'role_id', 'role'));
        } catch (\Exception $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back()->with('error', $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
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
    public function create(Request $request)
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

        $id_pengajuan = $request->id;

        $perusahaan = DB::table('mst_perusahaan_asuransi')
                        ->select('id', 'nama', 'alamat')
                        ->get();

        $host = env('LOS_API_HOST');
        $headers = [
            'token' => env('LOS_API_TOKEN')
        ];

        $apiPengajuan = $host . '/v1/get-list-pengajuan-by-id/' . $id_pengajuan;
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

        $pengajuan['no_aplikasi'] = \Str::random(10);
        $tenor = $pengajuan['tenor_yang_diminta'];
        $pengajuan['tgl_akhir_kredit'] = date('d-m-Y', strtotime($pengajuan['tanggal'] . " +$tenor month"));

        $skema_kredit = $pengajuan['skema_kredit'];
        $pengajuan['age'] = UtilityController::countAge($pengajuan['tanggal_lahir']);

        $jenisAsuransi = DB::table('mst_jenis_asuransi')
                            ->select('id', 'kode', 'jenis')
                            ->where('id', $request->jenis_asuransi)
                            ->first();

        return view('pages.asuransi-registrasi.create', compact('perusahaan', 'pengajuan', 'jenisAsuransi'));
    }

    public function review(Request $request)
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

        if ($role != 'Penyelia Kredit') {
            Alert::warning('Peringatan', 'Anda tidak memiliki akses.');
            return back();
        }

        $id_pengajuan = $request->id;
        $id_asuransi = $request->asuransi;

        $asuransi = DB::table('asuransi AS a')
                    ->select(
                        'a.id',
                        'a.perusahaan_asuransi_id',
                        'a.jenis_asuransi_id',
                        'a.no_aplikasi',
                        'a.no_rek',
                        'a.no_pk',
                        'a.premi',
                        'a.refund',
                        'd.jenis_pengajuan',
                        'd.kolektibilitas',
                        'd.jenis_pertanggungan',
                        'd.tipe_premi',
                        'd.jenis_coverage',
                        'd.tarif',
                        'd.kode_layanan_syariah',
                        'd.handling_fee',
                        'd.premi_disetor',
                        'd.no_polis_sebelumnya',
                        'd.baki_debet',
                        'd.tunggakan',
                    )
                    ->join('asuransi_detail AS d', 'd.asuransi_id', 'a.id')
                    ->where('a.id', $id_asuransi)
                    ->first();

        $perusahaan = DB::table('mst_perusahaan_asuransi')
                        ->select('id', 'nama', 'alamat')
                        ->where('id', $asuransi->perusahaan_asuransi_id)
                        ->first();

        $pendapat = DB::table('pendapat_asuransi AS p')
                        ->select('p.id', 'p.pendapat', 'p.created_at')
                        ->join('asuransi AS a', 'a.id', 'p.asuransi_id')
                        ->where('a.id', $asuransi->id)
                        ->orderBy('id', 'DESC')
                        ->get();

        $host = env('LOS_API_HOST');
        $headers = [
            'token' => env('LOS_API_TOKEN')
        ];

        $apiPengajuan = $host . '/v1/get-list-pengajuan-by-id/' . $id_pengajuan;
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

        $pengajuan['no_aplikasi'] = $asuransi->no_aplikasi;
        $tenor = $pengajuan['tenor_yang_diminta'];
        $pengajuan['tgl_akhir_kredit'] = date('d-m-Y', strtotime($pengajuan['tanggal'] . " +$tenor month"));

        $skema_kredit = $pengajuan['skema_kredit'];
        $pengajuan['age'] = UtilityController::countAge($pengajuan['tanggal_lahir']);

        $jenisAsuransi = DB::table('mst_jenis_asuransi')
                            ->select('id', 'kode', 'jenis')
                            ->where('id', $asuransi->jenis_asuransi_id)
                            ->first();

        return view('pages.asuransi-registrasi.review', compact('perusahaan', 'pengajuan', 'asuransi', 'jenisAsuransi', 'pendapat'));
    }

    public function reviewStore(Request $request) {
        $status = '';
        $message = '';
        $redirect_url = '';

        DB::beginTransaction();
        try {
            $name = \Session::get(config('global.user_name_session'));

            $id_asuransi = $request->id_asuransi;
            $type = $request->type; // approved or revition
            $now = date('Y-m-d H:i:s');

            DB::table('asuransi')
                ->where('id', $id_asuransi)
                ->update([
                    'status' => $type,
                    'updated_at' => $now,
                ]);

            if ($type == 'revition') {
                DB::table('pendapat_asuransi')->insert([
                    'asuransi_id' => $id_asuransi,
                    'pendapat' => $request->pendapat,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $this->logActivity->store('Pengguna ' . $name . ' menyimpan review asuransi.');

            DB::commit();
            $status = 'success';
            $message = 'Berhasil melakukan review';
            $redirect_url = route('asuransi.registrasi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'failed';
            $message = $e->getMessage();
            $redirect_url = back();
        } finally {
            $res = [
                'status' => $status,
                'message' => $message,
                'redirect_url' => $redirect_url,
            ];

            return response()->json($res);
        }
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
            $newAsuransi->perusahaan_asuransi_id = $request->perusahaan;
            $newAsuransi->no_aplikasi = $request->no_aplikasi;
            $newAsuransi->no_pk = $request->no_pk;
            $newAsuransi->no_rek = $request->no_rekening;
            $newAsuransi->premi = $premi;
            $newAsuransi->refund = $refund;
            $newAsuransi->kredit_id = $newKredit->id;
            $newAsuransi->jenis_asuransi_id = $jenis_asuransi_id;
            $newAsuransi->user_id = $user_id;
            $newAsuransi->nama_debitur = $request->nama_debitur;
            $newAsuransi->tanggal_awal = $tgl_awal;
            $newAsuransi->tanggal_akhir = $tgl_akhir;
            $newAsuransi->status = 'waiting approval';
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

            $this->logActivity->store('Pengguna ' . $request->name . ' menyimpan data asuransi.');

            DB::commit();
            Alert::success('Berhasil', 'Berhasil menyimpan data');
            return redirect()->route('asuransi.registrasi.index');
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

    public function send(Request $request) {
        ini_set('max_execution_time', 120);
        try {
            $id_asuransi = $request->id;
            $asuransi = DB::table('asuransi AS a')
                        ->select(
                            'k.id AS kredit_id',
                            'k.pengajuan_id',
                            'a.id',
                            'a.perusahaan_asuransi_id',
                            'a.jenis_asuransi_id',
                            'a.no_aplikasi',
                            'a.no_rek',
                            'a.no_pk',
                            'a.premi',
                            'a.refund',
                            'd.jenis_pengajuan',
                            'd.kolektibilitas',
                            'd.jenis_pertanggungan',
                            'd.tipe_premi',
                            'd.jenis_coverage',
                            'd.tarif',
                            'd.kode_layanan_syariah',
                            'd.handling_fee',
                            'd.premi_disetor',
                            'd.no_polis_sebelumnya',
                            'd.baki_debet',
                            'd.tunggakan',
                        )
                        ->join('asuransi_detail AS d', 'd.asuransi_id', 'a.id')
                        ->join('kredits AS k', 'k.id', 'a.kredit_id')
                        ->where('a.id', $id_asuransi)
                        ->first();

            $host = env('LOS_API_HOST');
            $headers = [
                'token' => env('LOS_API_TOKEN')
            ];

            $apiPengajuan = $host . '/v1/get-list-pengajuan-by-id/' . $asuransi->pengajuan_id;
            $api_req = Http::timeout(20)->withHeaders($headers)->get($apiPengajuan);
            $response = json_decode($api_req->getBody(), true);
            $pengajuan = null;
            if ($response) {
                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $pengajuan = $response['data'];
                    }
                }
            }

            $pengajuan['no_aplikasi'] = $asuransi->no_aplikasi;
            $tenor = $pengajuan['tenor_yang_diminta'];
            $pengajuan['tgl_akhir_kredit'] = date('d-m-Y', strtotime($pengajuan['tanggal'] . " +$tenor month"));

            $skema_kredit = $pengajuan['skema_kredit'];
            $pengajuan['age'] = UtilityController::countAge($pengajuan['tanggal_lahir']);

            $jenisAsuransi = DB::table('mst_jenis_asuransi')
                    ->select('id', 'kode', 'jenis')
                    ->where('id', $asuransi->jenis_asuransi_id)
                    ->first();

            $perusahaan = DB::table('mst_perusahaan_asuransi')
                        ->select('id', 'nama', 'alamat')
                        ->where('id', $asuransi->perusahaan_asuransi_id)
                        ->first();

            $jenis_asuransi_option = explode('-', $jenisAsuransi->id.'-'.$jenisAsuransi->kode);
            $req = [
                "no_aplikasi"=> $asuransi->no_aplikasi,
                "no_rekening"=> $asuransi->no_rek,
                "jenis_asuransi"=> $jenis_asuransi_option[1],
                "tgl_pengajuan"=> date("Y-m-d", strtotime($pengajuan['tanggal'])) ,
                "tgl_jatuhtempo"=> date("Y-m-d", strtotime($pengajuan['tgl_akhir_kredit'])),
                "kd_uker"=> $pengajuan['kode_cabang'],
                "nama_debitur"=> $pengajuan['nama'],
                "alamat_debitur"=> $pengajuan['alamat_rumah'],
                "tgl_lahir"=> date("Y-m-d", strtotime($pengajuan['tanggal_lahir'])),
                "no_ktp"=> $pengajuan['no_ktp'],
                "no_pk"=> $pengajuan['no_pk'],
                "tgl_pk"=> $pengajuan['tgl_cetak_pk'],
                "plafon_kredit"=> UtilityController::clearCurrencyFormat($pengajuan['jumlah_kredit']),
                "tgl_awal_kredit"=> date("Y-m-d",strtotime($pengajuan['tanggal'])),
                "tgl_akhir_kredit"=> date("Y-m-d", strtotime($pengajuan['tgl_akhir_kredit'])),
                "jml_bulan"=> $pengajuan['tenor_yang_diminta'],
                "jenis_pengajuan"=> $asuransi->jenis_pengajuan,
                "bade"=> strval(intval($asuransi->baki_debet)),
                "tunggakan"=> strval(intval($asuransi->tunggakan)),
                "kolektibilitas"=> $asuransi->kolektibilitas,
                "no_polis_sebelumnya"=> $asuransi->no_polis_sebelumnya,
                "jenis_pertanggungan"=> $asuransi->jenis_pertanggungan,
                "tipe_premi"=> $asuransi->tipe_premi,
                "premi"=> UtilityController::clearCurrencyFormat(strval(intval($asuransi->premi))),
                "jenis_coverage"=> $asuransi->jenis_coverage,
                "tarif"=> $asuransi->tarif,
                "refund"=> UtilityController::clearCurrencyFormat(strval(intval($asuransi->refund))),
                "kode_ls"=> $asuransi->kode_layanan_syariah,
                // "jenis_kredit"=> $request->get('jenis_kredit'),
                "jenis_kredit"=> "01",
                "handling_fee"=> UtilityController::clearCurrencyFormat(strval(intval($asuransi->handling_fee))),
                "premi_disetor"=> UtilityController::clearCurrencyFormat(strval(intval($asuransi->premi_disetor))),
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

                            // update asuransi
                            $newAsuransi = Asuransi::find($id_asuransi);
                            $newAsuransi->no_polis = $polis;
                            $newAsuransi->tgl_polis = $tgl_polis;
                            $newAsuransi->tgl_rekam = $tgl_rekam;
                            $newAsuransi->status = 'sended';
                            $newAsuransi->save();

                            $this->logActivity->store('Pengguna ' . $request->name . ' mengirimkan registrasi asuransi.');

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
                        case '07':
                            # perhitungan premi tidak sesuai
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
                if ($asuransi->is_paid) {
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
                    Alert::warning('Peringatan', 'Harap melakukan pembayaran premi terlebih dahulu');
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

    public function detail($id){
        $dataDebitur = DB::table('asuransi')->where('id', $id)->first();
        $dataRegister = DB::table('asuransi_detail')->where('asuransi_id', $id)->first();

        return view('pages.asuransi-registrasi.detail', compact('dataDebitur', 'dataRegister'));
    }

    public function edit($id){
        $host = env('LOS_API_HOST');
        $headers = [
            'token' => env('LOS_API_TOKEN')
        ];
        try {
            $apiURL = $host . '/v1/get-list-pengajuan-by-id/' . $id;

            try {
                $response = Http::timeout(6)->withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);
                // return $responseBody;

                if ($responseBody['status'] == "success") {
                    $data = $responseBody['data'];
                    $data['age'] = UtilityController::countAge($data['tanggal_lahir']);
                    $jenis_asuransi = DB::table('mst_jenis_asuransi')
                        ->select('id', 'jenis')
                        ->where('jenis_kredit', $data['skema_kredit'])
                        ->orderBy('jenis')
                        ->first();

                    $asuransi = DB::table('asuransi')
                        ->join('kredits AS k', 'k.id', 'asuransi.kredit_id')
                        ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'asuransi.jenis_asuransi_id')
                        ->join('mst_perusahaan_asuransi AS p', 'p.id', 'asuransi.perusahaan_asuransi_id')
                        ->join('asuransi_detail AS d', 'd.asuransi_id', 'asuransi.id')
                        ->select(
                            'p.nama AS perusahaan',
                            'asuransi.*',
                            'mst_jenis_asuransi.jenis',
                            'k.kode_cabang',
                            'd.tarif',
                            'd.premi_disetor',
                            'd.handling_fee',
                            'd.kolektibilitas',
                            'd.jenis_pengajuan',
                            'd.jenis_pertanggungan',
                            'd.tipe_premi',
                            'd.tunggakan',
                            'd.baki_debet',
                            'd.jenis_coverage',
                            'd.kode_layanan_syariah',
                        )
                        ->where('asuransi.jenis_asuransi_id', $jenis_asuransi->id);

                    $asuransi = $asuransi->groupBy('no_pk')
                        ->orderBy('no_aplikasi')
                        ->first();
                    $jenis_asuransi->asuransi = $asuransi;
                    $pendapat = DB::table('pendapat_asuransi')->where('asuransi_id', $jenis_asuransi->asuransi->id)->orderBy('created_at', 'DESC')->get();
                    $perusahaan = DB::table('mst_perusahaan_asuransi')
                    ->select('id', 'nama', 'alamat')
                    ->get();

                // return ['data' => $data, 'jenis' => $jenis_asuransi];
                } else {
                    return 'gagal';
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // return $e->getMessage();
            }


            return view('pages.asuransi-registrasi.edit', compact('data', 'jenis_asuransi', 'pendapat', 'perusahaan'));
        } catch (\Exception $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back()->with('error', $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan pada database. ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id){
        // return ['id' => $id, $request->all()];
        DB::beginTransaction();
        try {
            $premi = UtilityController::clearCurrencyFormat($request->get('premi'));
            $refund = UtilityController::clearCurrencyFormat($request->get('refund'));

            $editAsuransi = Asuransi::find($id);
            $editAsuransi->no_rek = $request->no_rekening;
            $editAsuransi->premi = $premi;
            $editAsuransi->refund = $refund;
            $editAsuransi->status = 'waiting approval';
            $editAsuransi->save();

            $editDetailAsuransi = DetailAsuransi::where('asuransi_id', $id)->first();
            $editDetailAsuransi->jenis_pengajuan = $request->jenis_pengajuan;
            $editDetailAsuransi->kolektibilitas = $request->kolektibilitas;
            $editDetailAsuransi->jenis_pertanggungan = $request->jenis_pertanggungan;
            $editDetailAsuransi->tipe_premi = $request->tipe_premi;
            $editDetailAsuransi->jenis_coverage = $request->jenis_coverage;
            $editDetailAsuransi->kode_layanan_syariah = $request->kode_ls;
            $editDetailAsuransi->no_polis_sebelumnya = $request->no_polis_sebelumnya;
            $editDetailAsuransi->tarif = $request->tarif;
            $editDetailAsuransi->handling_fee = UtilityController::clearCurrencyFormat($request->handling_fee);
            $editDetailAsuransi->baki_debet = UtilityController::clearCurrencyFormat($request->baki_debet);
            $editDetailAsuransi->tunggakan = UtilityController::clearCurrencyFormat($request->tunggakan);
            $editDetailAsuransi->premi_disetor = UtilityController::clearCurrencyFormat($request->premi_disetor);
            $editDetailAsuransi->save();


        DB::commit();
        Alert::success('Berhasil', 'Berhasil edit data');
        return redirect()->route('asuransi.registrasi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return redirect()->route('asuransi.registrasi.index')->with('error', $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return redirect()->route('asuransi.registrasi.index')->with('error', 'Terjadi kesalahan pada database. ' . $e->getMessage());
        }
    }
}
