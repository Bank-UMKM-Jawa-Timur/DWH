<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\Asuransi;
use App\Models\PembayaranPremi;
use App\Models\PembayaranPremiDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class PembayaranPremiController extends Controller
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
        $param['role_id'] = $role_id;
        $param['role'] = $role;

        $param['title'] = 'Pembayaran Premi';
        $param['pageTitle'] = 'Pembayaran Premi';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;

        return view('pages.pembayaran_premi.index', $param);
    }

    public function list($page_length = 5 , $searchQuery, $searchBy)
    {
        $query = DB::table('pembayaran_premi_detail AS d')
                    ->select(
                        'a.id AS asuransi_id',
                        'p.id AS pembayaran_premi_id',
                        'd.id AS detail_id',
                        'a.no_aplikasi',
                        'a.no_polis',
                        'p.nobukti_pembayaran',
                        'p.tgl_bayar',
                        'p.total_premi',
                        'd.no_rek',
                        'd.no_pk',
                        'd.periode_bayar',
                        'd.total_periode'
                    )
                    ->join('pembayaran_premi AS p', 'p.id', 'd.pembayaran_premi_id')
                    ->join('asuransi AS a', 'a.id', 'd.asuransi_id');
        if ($searchQuery && $searchBy == 'field') {
            $query->where('a.no_aplikasi', 'like', '%' . $searchQuery . '%')
                ->orWhere('a.no_polis', 'like', '%' . $searchQuery . '%')
                ->orWhere('p.nobukti_pembayaran', 'like', '%' . $searchQuery . '%')
                ->orWhere('p.tgl_bayar', 'like', '%' . $searchQuery . '%')
                ->orWhere('p.total_premi', 'like', '%' . $searchQuery . '%')
                ->orWhere('d.no_rek', 'like', '%' . $searchQuery . '%')
                ->orWhere('d.no_pk', 'like', '%' . $searchQuery . '%')
                ->orWhere('d.periode_bayar', 'like', '%' . $searchQuery . '%')
                ->orWhere('d.total_periode', 'like', '%' . $searchQuery . '%');
        }
        if (is_numeric($page_length)) {
            $data = $query->paginate($page_length);
        } else {
            $data = $query->get();
        }

        return $data;
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

        $param['noAplikasi'] = DB::table('asuransi')->select('asuransi.no_aplikasi', 'asuransi.nama_debitur','jenis.jenis')
        ->join('mst_jenis_asuransi as jenis', 'asuransi.jenis_asuransi_id', 'jenis.id')
        ->where('status', 'sended')->groupBy('no_aplikasi')
        ->get();

        $param['jenisAsuransi'] = DB::table('asuransi')->select('asuransi.*', 'jenis.jenis')
        ->join('mst_jenis_asuransi as jenis', 'asuransi.jenis_asuransi_id', 'jenis.id')
        ->where('status', 'sended')
        ->get();

        return view('pages.pembayaran_premi.create', $param);
    }

    public function getJenisByNoAplikasi(Request $request){
        $jenis = DB::table('asuransi')
                ->select('asuransi.*', 'd.premi_disetor', 'jenis.jenis', DB::raw("LEFT(UUID(), 8) AS generate_key"))
                ->join('mst_jenis_asuransi as jenis', 'asuransi.jenis_asuransi_id', '=', 'jenis.id')
                ->join('asuransi_detail as d', 'asuransi.id', '=', 'd.asuransi_id')
                ->where('asuransi.status', 'sended')
                ->where('asuransi.is_paid', 0)
                ->where('asuransi.no_aplikasi', $request->no_aplikasi)
                ->get();

        return response()->json([
            'data' => $jenis
        ]);
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

        $req = $request->all();

        $fields = Validator::make($req, [
            "row_periode_bayar" => "required",
            "row_total_periode_bayar" => "required",
        ], [
            'required' => 'Atribut :attribute harus diisi.',
        ]);

        $noBuktiPembayaran =  $request->input('no_bukti_pembayaran');
        $tglBayar =  $request->input('tgl_bayar');
        $noPolisArray =  $request->input('row_no_polis');
        $premiArray = $request->input('row_premi');
        $totalPremi = $request->input('total_premi');
        $idNoAplikasiArray = $request->input('row_id_no_aplikasi');
        $noAplikasiArray = $request->input('row_no_aplikasi');
        $noRekArray = $request->input('row_no_rek');
        $noPkArray = $request->input('row_no_pk');
        $periodeBayarArray = $request->input('row_periode_bayar');
        $totalPeriodeArray = $request->input('row_total_periode_bayar');

        DB::beginTransaction();
        try {
            $url = 'http://sandbox-umkm.ekalloyd.id:8387/bayar';

            $header = [
                'Content-Type' => 'application/json',
                'X-API-Key' => 'elj-bprjatim-123',
                'Accept' => 'application/json',
                "Access-Control-Allow-Origin" => "*",
                "Access-Control-Allow-Methods" => "*"
            ];

            $arr_detail = [];
            for ($i=0; $i < count($premiArray); $i++) {
                $d = [
                    "premi" => $premiArray[$i],
                    "no_rek" => $noRekArray[$i],
                    "no_aplikasi" => $noAplikasiArray[$i],
                    "no_pk" => $noPkArray[$i],
                    "no_polis" => $noPolisArray[$i],
                    "periode_bayar" => $periodeBayarArray[$i],
                    "total_periode" => $totalPeriodeArray[$i]
                ];

                array_push($arr_detail, $d);
            }
            $body = [
                "nobukti_pembayaran" => $noBuktiPembayaran,
                "tgl_bayar" => date('Y-m-d', strtotime($tglBayar)),
                "total_premi" => $totalPremi,
                "rincian_bayar" => $arr_detail
            ];

            if ($tglBayar != "dd/mm/yyyy") {
                if ($idNoAplikasiArray != null) {
                    $objekTanggal = Carbon::createFromFormat('d-m-Y', $tglBayar);
                    if ($fields->fails()) {
                        $errors = $fields->errors()->all();
                        $errorMessage = implode('<br>', $errors);
                        Alert::error('Gagal', $errorMessage);
                        return back()->withInput();
                    }else{
                        try {
                            $response = Http::timeout(60)->withHeaders($header)->withOptions(['verify' => false])->post($url, $body);

                            $statusCode = $response->status();
                            if ($statusCode == 200) {
                                $responseBody = json_decode($response->getBody(), true);
                                $status = $responseBody['status'];
                                $message = '';
                                if ($status == "00") {
                                    // simpan ke db
                                    // db pembayaran premi
                                    $createPremi = new PembayaranPremi();
                                    $createPremi->nobukti_pembayaran = $noBuktiPembayaran;
                                    $createPremi->tgl_bayar = date('Y-m-d', strtotime($tglBayar));
                                    $createPremi->total_premi = $totalPremi;
                                    $createPremi->save();
                                    $noPolisDibayar = '';
                                    foreach ($premiArray as $key => $premi) {
                                        // db pembayaran premi detail
                                        $createPremiDetail = new PembayaranPremiDetail();
                                        $createPremiDetail->pembayaran_premi_id = $createPremi->id;
                                        $createPremiDetail->asuransi_id = $idNoAplikasiArray[$key];
                                        $createPremiDetail->no_rek = $noRekArray[$key];
                                        $createPremiDetail->no_pk = $noPkArray[$key];
                                        $createPremiDetail->periode_bayar = $periodeBayarArray[$key];
                                        $createPremiDetail->total_periode = $totalPeriodeArray[$key];
                                        $createPremiDetail->save();

                                        // db update asuransi
                                        $asuransi = Asuransi::find($idNoAplikasiArray[$key]);
                                        $asuransi->is_paid = true;
                                        $asuransi->save();

                                        $comma = ($key + 1) < count($premiArray) ? ',' : '';
                                        $noPolisDibayar .= $asuransi->no_polis." $comma";
                                    }
                                    $this->logActivity->store('Pengguna ' . $request->name . ' melakukan pembayaran premi pada nomor polis '. $noPolisDibayar);

                                    $message = $responseBody['keterangan'];
                                    DB::commit();
                                    Alert::success('Berhasil', $message);
                                    return redirect()->route('asuransi.pembayaran-premi.index');
                                }
                                else{
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
                }else{
                    Alert::warning('Peringatan!', 'Silahkan pilih no aplikasi terlebih dahulu');
                    return back();
                }
            }else{
                Alert::warning('Peringatan!', 'Tanggal Bayar harus di pilih.');
                return back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal', $e->getMessage());
            return back();
        }
    }

    public function formatCurrency($number)
    {
        $formattedNumber = number_format($number, 0, ',', '.');
        $formattedNumber = 'Rp ' . $formattedNumber;
        return $formattedNumber;
    }

    public function storeInquery(Request $request)
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

        $req = [
            "no_aplikasi" => $request->input('row_no_aplikasi'),
            "nobukti_pembayaran" => $request->input('row_nobukti_pembayaran'),
            "no_rekening" => $request->input('row_no_rek'),
            "outstanding" => $request->input('row_outstanding'),
            "periode_premi" => $request->input('row_periode_premi'),
            "no_polis" => $request->input('row_no_polis'),
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
            $url = "$host/query2";
            $response = Http::withHeaders($headers)->withOptions(['verify' => false])->post($url, $req);
            $statusCode = $response->status();
            if ($statusCode == 200) {
                $responseBody = json_decode($response->getBody(), true);
                $status = $responseBody['status'];
                $message = '';
                if ($status == "00") {
                    $message = $responseBody['keterangan'];
                    $nilai = $responseBody['nilai_premi'];
                    $this->logActivity->store('Pengguna ' . $request->name . ' melakukan inquery pembayaran premi.');
                    Alert::success('Berhasil', $message . ', Nilai Premi ' . $this->formatCurrency($nilai));
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
