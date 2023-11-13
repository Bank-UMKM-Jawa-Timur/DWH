<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Utils\UtilityController;
use App\Models\DetailPengajuanKlaim;
use App\Models\PendapatPengajuanKlaim;
use App\Models\PengajuanKlaim;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use PDO;
use stdClass;

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
        $data = DB::table('pengajuan_klaim AS p')->select(
                                    'p.id',
                                    'p.stat_klaim',
                                    'p.status',
                                    'a.no_aplikasi',
                                    'a.no_rek',
                                    'a.no_polis'
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

        return view('pages.pengajuan-klaim.index', compact('data', 'role_id', 'role'));
    }
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

        $dataNoRek = DB::table('asuransi')->orderBy('no_aplikasi')->get();
        return view('pages.pengajuan-klaim.create', compact('dataNoRek'));
    }

    public function addPengajuan($id, Request $request){
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

        $dataNoRek = DB::table('asuransi')->orderBy('no_aplikasi')->where('id', $id)->get();
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

        DB::beginTransaction();
        try {
            $asuransi = DB::table('asuransi')
                            ->select('id')
                            ->where('no_aplikasi', $request->no_aplikasi)
                            ->first();

            $newPengajuanKlaim = new PengajuanKlaim();
            $newPengajuanKlaim->asuransi_id = $asuransi->id;
            $newPengajuanKlaim->stat_klaim = 1; // sedang diproses
            $newPengajuanKlaim->status = 'waiting approval';
            $newPengajuanKlaim->save();

            $detail = [
                'pengajuan_klaim_id' => $newPengajuanKlaim->id,
                'no_sp3' => $request->get('no_sp3'),
                'tgl_sp3' => date("Y-m-d", strtotime($request->get('tgl_sp3'))),
                'tunggakan_pokok' => UtilityController::clearCurrencyFormat($request->get('tunggakan_pokok')),
                'tunggakan_bunga' => UtilityController::clearCurrencyFormat($request->get('tunggakan_bunga')),
                'tunggakan_denda' => UtilityController::clearCurrencyFormat($request->get('tunggakan_denda')),
                'nilai_pengikatan' => UtilityController::clearCurrencyFormat($request->get('nilai_pengikatan')),
                'nilai_tuntutan_klaim' => UtilityController::clearCurrencyFormat($request->get('nilai_tuntutan_klaim')),
                'kode_agunan' => $request->get('jenis_agunan'),
                'penyebab_klaim' => $request->get('penyebab_klaim'),
                'created_at' => now()
            ];

            $detailPengajuanKlaim = new DetailPengajuanKlaim();
            $detailPengajuanKlaim->insert($detail);

            $user_name = \Session::get(config('global.user_name_session'));
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $name = $token ? $user['data']['nip'] : $user->email;

            $this->logActivity->storeAsuransi('Pengguna ' . $user_name . '(' . $name . ')' . ' menambahkan pengajuan klaim.', $asuransi->id, 1);

            DB::commit();
            Alert::success('Berhasil', 'Berhasil melakukan pengajuan klaim.');
            return redirect()->route('asuransi.registrasi.index');
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
            "no_aplikasi" => $request->no_aplikasi,
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

                    $this->logActivity->store('Pengguna ' . $request->name . ' cek status pengajuan klaim', Null, '0');

                    DB::commit();
                    return response()->json([
                        'status' => 'Berhasil',
                        'response' => $responseBody
                    ]);
                }else{
                    return response()->json([
                        'status' => 'Gagal',
                        'message' => $responseBody['keterangan']
                    ]);
                }
            }
            else {
                return response()->json([
                    'status' => 'Gagal',
                    'message' => 'Terjadi kesalahan.'
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function pembatalanKlaim(Request $request){
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

        $req = [
            'no_aplikasi' => $request->no_aplikasi,
            'no_rekening' => $request->no_rek,
            'no_sp' => $request->no_sp,
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
            $user_name = \Session::get(config('global.user_name_session'));
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $name = $token ? $user['data']['nip'] : $user->email;

            $host = config('global.eka_lloyd_host');
            $url = "$host/batal2";
            $response = Http::withHeaders($headers)->withOptions(['verify' => false])->post($url, $req);
            $statusCode = $response->status();
            if ($statusCode == 200) {
                $responseBody = json_decode($response->getBody(), true);
                $code = $responseBody['status'];
                $message = '';
                switch($code){
                    case '01':
                        $message = $responseBody['keterangan'];
                        Alert::error('Gagal', $message);
                        return redirect()->route('asuransi.registrasi.index');
                        break;
                    case '02':
                        $message = $responseBody['keterangan'];
                        Alert::error('Gagal', $message);
                        return redirect()->route('asuransi.registrasi.index');
                        break;
                    case '03':
                        $message = $responseBody['keterangan'];
                        Alert::error('Gagal', $message);
                        return redirect()->route('asuransi.registrasi.index');
                        break;
                    case '05':
                        $message = $responseBody['keterangan'];
                        $data = PengajuanKlaim::find($request->id);

                        $pengajuanKlaim = PengajuanKlaim::find($request->id);
                        $pengajuanKlaim->canceled_at = date('Y-m-d');
                        $pengajuanKlaim->canceled_by = $user_id;
                        $pengajuanKlaim->status = 'canceled';
                        $pengajuanKlaim->save();

                        $this->logActivity->storeAsuransi('Pengguna ' . $user_name . '(' . $name . ')' . ' melakukan pembatalan pengajuan klaim.', $data->asuransi_id, 1);

                        DB::commit();
                        Alert::success('Berhasil', $message);
                        return redirect()->route('asuransi.registrasi.index');
                        break;
                    case '06':
                        $message = $responseBody['keterangan'];
                        Alert::error('Gagal', $message);
                        return redirect()->route('asuransi.registrasi.index');
                        break;
                    case '48':
                        $message = $responseBody['keterangan'];
                        Alert::error('Gagal', $message);
                        return redirect()->route('asuransi.registrasi.index');
                        break;
                    default:
                        Alert::error('Gagal', 'Terjadi kesalahan');
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

    public function reviewPenyelia($id){
        try{
            $data['data'] = DB::table('asuransi as a')
                ->join('pengajuan_klaim as p', 'p.asuransi_id', 'a.id')
                ->join('pengajuan_klaim_detail as d', 'd.pengajuan_klaim_id', 'p.id')
                ->select('a.no_polis', 'a.no_rek', 'a.no_aplikasi', 'd.*')
                ->where('p.id', $id)
                ->first();
            $data['pendapat'] = PendapatPengajuanKlaim::where('pengajuan_klaim_id', $id)
                ->get();

            if($data['data']){
                return view('pages.pengajuan-klaim.review', $data);
            } else{
                Alert::error('Gagal', 'Data tidak ditemukan');
                return back();
            }
        } catch(Exception $e){
            Alert::error('Gagal', $e->getMessage());
            return back();

        } catch(QueryException $e){
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }

    public function approval($id, Request $request){
        DB::beginTransaction();
        try{
            $pengajuanKlaim = PengajuanKlaim::find($id);
            if($pengajuanKlaim){
                $pengajuanKlaim->status = 'approved';
                $pengajuanKlaim->save();

                $data = PengajuanKlaim::find($id);
                $user_name = \Session::get(config('global.user_name_session'));
                $token = \Session::get(config('global.user_token_session'));
                $user = $token ? $this->getLoginSession() : Auth::user();
                $name = $token ? $user['data']['nip'] : $user->email;

                $this->logActivity->storeAsuransi('Pengguna ' . $user_name . '(' . $name . ')' . ' menyetujui pengajuan klaim.', $data->asuransi_id, 1);

                DB::commit();

                Alert::success('Berhasil', 'Berhasil melakukan review pengajuan klaim');
                return redirect()->route('asuransi.registrasi.index');
            } else{
                Alert::error('Gagal', 'Data tidak ditemukan');
                return back();
            }
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Gagal', $e->getMessage());
            return back();
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }

    public function kembalikanKeStaf($id, Request $request){
        DB::beginTransaction();
        try{
            PendapatPengajuanKlaim::insert([
                'pengajuan_klaim_id' => $id,
                'pendapat' => $request->pendapat,
                'created_at' => now()
            ]);

            $pengajuanKlaim = PengajuanKlaim::find($id);
            $pengajuanKlaim->status = 'revition';
            $pengajuanKlaim->save();

            $data = PengajuanKlaim::find($id);
            $user_name = \Session::get(config('global.user_name_session'));
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $name = $token ? $user['data']['nip'] : $user->email;

            $this->logActivity->storeAsuransi('Pengguna ' . $user_name . '(' . $name . ')' . ' mengembalikan pengajuan klaim ke staf.', $data->asuransi_id, 1);

            DB::commit();

            return response()->json([
                'status' => 'Berhasil',
                'message' => 'Berhasil mengembalikan posisi ke staf'
            ]);
        } catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e->getMessage()
            ]);
        } catch(QueryException $e){
            DB::rollBack();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id){
        try{
            $data['data'] = DB::table('asuransi as a')
                ->join('pengajuan_klaim as p', 'p.asuransi_id', 'a.id')
                ->join('pengajuan_klaim_detail as d', 'd.pengajuan_klaim_id', 'p.id')
                ->select('a.no_polis', 'a.no_rek', 'a.no_aplikasi', 'd.*')
                ->where('p.id', $id)
                ->first();
            $data['pendapat'] = PendapatPengajuanKlaim::where('pengajuan_klaim_id', $id)
                ->get();

            if($data['data']){
                return view('pages.pengajuan-klaim.edit', $data);
            } else{
                Alert::error('Gagal', 'Data tidak ditemukan');
                return back();
            }
        } catch(Exception $e){
            Alert::error('Gagal', $e->getMessage());
            return back();

        } catch(QueryException $e){
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }

    public function update($id, Request $request){
        DB::beginTransaction();
        try{
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

            $data = DetailPengajuanKlaim::where('pengajuan_klaim_id', $id)->first();
            $data->no_sp3 = $request->get('no_sp3');
            $data->tgl_sp3 = date("Y-m-d", strtotime($request->get('tgl_sp3')));
            $data->tunggakan_pokok = UtilityController::clearCurrencyFormat($request->get('tunggakan_pokok'));
            $data->tunggakan_bunga = UtilityController::clearCurrencyFormat($request->get('tunggakan_bunga'));
            $data->tunggakan_denda = UtilityController::clearCurrencyFormat($request->get('tunggakan_denda'));
            $data->nilai_pengikatan = UtilityController::clearCurrencyFormat($request->get('nilai_pengikatan'));
            $data->nilai_tuntutan_klaim = UtilityController::clearCurrencyFormat($request->get('nilai_tuntutan_klaim'));
            $data->kode_agunan = $request->get('jenis_agunan');
            $data->penyebab_klaim = $request->get('penyebab_klaim');
            $data->save();

            $dataPengajuanKlaim = PengajuanKlaim::find($id);
            $dataPengajuanKlaim->status = 'waiting approval';
            $dataPengajuanKlaim->save();

            $user_name = \Session::get(config('global.user_name_session'));
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $name = $token ? $user['data']['nip'] : $user->email;

            $this->logActivity->storeAsuransi('Pengguna ' . $user_name . '(' . $name . ')' . ' mengedit pengajuan klaim.', $data->asuransi_id, 1);
            DB::commit();

            Alert::success('Berhasil', 'Berhasil mengedit pengajuan klaim.');
            return redirect()->route('asuransi.pengajuan-klaim.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Gagal', $e->getMessage());
            return back();
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }

    public function hitEndpoint($id, Request $request){
        try{
            $data = DB::table('asuransi as a')
                ->join('pengajuan_klaim as p', 'p.asuransi_id', 'a.id')
                ->join('pengajuan_klaim_detail as d', 'd.pengajuan_klaim_id', 'p.id')
                ->select('a.no_polis', 'a.no_rek', 'a.no_aplikasi', 'd.*')
                ->where('p.id', $id)
                ->first();

            $headers = [
                "Accept" => "/",
                "x-api-key" => config('global.eka_lloyd_token'),
                "Content-Type" => "application/json",
                "Access-Control-Allow-Origin" => "*",
                "Access-Control-Allow-Methods" => "*"
            ];
            $req = [
                'no_aplikasi' => $data->no_aplikasi,
                'no_rekening' => $data->no_rek,
                'no_sp' => $data->no_polis,
                'no_sp3' => $data->no_sp3,
                'tgl_sp3' => date("Y-m-d", strtotime($data->tgl_sp3)),
                'tunggakan_pokok' => UtilityController::clearCurrencyFormat($data->tunggakan_pokok),
                'tunggakan_bunga' => UtilityController::clearCurrencyFormat($data->tunggakan_bunga),
                'tunggakan_denda' => UtilityController::clearCurrencyFormat($data->tunggakan_denda),
                'nilai_pengikatan' => UtilityController::clearCurrencyFormat($data->nilai_pengikatan),
                'nilai_tuntutan_klaim' => UtilityController::clearCurrencyFormat($data->nilai_tuntutan_klaim),
                'jenis_agunan' => $data->kode_agunan,
                'penyebab_klaim' => $data->penyebab_klaim,
            ];

            // return $req;

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

                            $pengajuanKlaim = PengajuanKlaim::find($id);
                            $pengajuanKlaim->status = 'sended';
                            $pengajuanKlaim->save();

                            $data = PengajuanKlaim::find($id);

                            $user_name = \Session::get(config('global.user_name_session'));
                            $token = \Session::get(config('global.user_token_session'));
                            $user = $token ? $this->getLoginSession() : Auth::user();
                            $name = $token ? $user['data']['nip'] : $user->email;

                            $this->logActivity->storeAsuransi('Pengguna ' . $user_name . '(' . $name . ')' . ' mengirim pengajuan klaim.', $data->asuransi_id, 1);

                            DB::commit();
                            Alert::success('Berhasil', $message);
                            return back();
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
        } catch(Exception $e){
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }
}
