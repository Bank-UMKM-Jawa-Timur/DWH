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
        $query = PembayaranPremiDetail::with('pembayaranPremi')
                        ->orderBy('id');
        if ($searchQuery && $searchBy === 'field') {
            $query->whereHas('pembayaranPremi', function ($q) use ($searchQuery) {
                $q->where('no_rek', 'like', '%' . $searchQuery . '%')
                    ->orWhere('nobukti_pembayaran', 'like', '%' . $searchQuery . '%')
                    ->orWhere('tgl_bayar', 'like', '%' . $searchQuery . '%')
                    ->orWhere('total_premi', 'like', '%' . $searchQuery . '%')
                    ->orWhere('no_rek', 'like', '%' . $searchQuery . '%')
                    ->orWhere('no_pk', 'like', '%' . $searchQuery . '%')
                    ->orWhere('periode_bayar', 'like', '%' . $searchQuery . '%')
                    ->orWhere('total_periode', 'like', '%' . $searchQuery . '%');
            });
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
        $param['noAplikasi'] = DB::table('asuransi')->select('asuransi.no_aplikasi','jenis.jenis')
        ->join('mst_jenis_asuransi as jenis', 'asuransi.jenis_asuransi_id', 'jenis.id')
        ->where('status', 'onprogress')->groupBy('no_aplikasi')
        ->get();

        $param['jenisAsuransi'] = DB::table('asuransi')->select('asuransi.*', 'jenis.jenis')
        ->join('mst_jenis_asuransi as jenis', 'asuransi.jenis_asuransi_id', 'jenis.id')
        ->where('status', 'onprogress')
        ->get();

        return view('pages.pembayaran_premi.create', $param);
    }

    public function getJenisByNoAplikasi(Request $request){
        $jenis = DB::table('asuransi')
                ->select('asuransi.*', 'jenis.jenis', DB::raw("LEFT(UUID(), 8) AS generate_key"))
                ->join('mst_jenis_asuransi as jenis', 'asuransi.jenis_asuransi_id', '=', 'jenis.id')
                ->where('asuransi.status', 'onprogress')
                ->where('asuransi.is_paid', false)
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

         // $tgl_bayar = Carbon::now()->format('Y-m-d');
        // $total_premi = 472500;
        // $no_rek = "100605720000011";
        // $no_aplikasi = "BWj5FFUZfG";
        // $no_pk = "PK\/0085\/73\/SH\/0817-0820";
        // $periode_bayar = "1";
        // $total_periode = "10";

        $req = $request->all();

        $fields = Validator::make($req, [
            "row_periode_bayar" => "required",
            "row_total_periode_bayar" => "required",
        ], [
            'required' => 'Atribut :attribute harus diisi.',
        ]);

        $noBuktiPembayaranArray =  $request->input('row_nobukti_pembayaran');
        $tglBayar =  $request->input('tgl_bayar');
        $noPolisArray =  $request->input('row_no_polis');
        $premiArray = $request->input('row_premi');
        $idNoAplikasiArray = $request->input('row_id_no_aplikasi');
        $noAplikasiArray = $request->input('row_no_aplikasi');
        $noRekArray = $request->input('row_no_rek');
        $noPkArray = $request->input('row_no_pk');
        $periodeBayarArray = $request->input('row_periode_bayar');
        $totalPeriodeArray = $request->input('row_total_periode_bayar');

        $url = 'http://sandbox-umkm.ekalloyd.id:8387/bayar';

        $header = [
            'Content-Type' => 'application/json',
            'X-API-Key' => 'elj-bprjatim-123',
            'Accept' => 'application/json',
            "Access-Control-Allow-Origin" => "*",
            "Access-Control-Allow-Methods" => "*"
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
                        foreach ($premiArray as $key => $premi) {
                            $response = Http::withHeaders($header)->withOptions(['verify' => false])->post($url, 
                            [
                                "nobukti_pembayaran" => $noBuktiPembayaranArray[$key],
                                "tgl_bayar" => $objekTanggal->format('Y-m-d'),
                                "total_premi" => $premi,
                                "rincian_bayar" => [
                                    [
                                        "premi" => $premi,
                                        "no_rek" => $noRekArray[$key],
                                        "no_aplikasi" => $noAplikasiArray[$key],
                                        "no_pk" => $noPkArray[$key],
                                        "no_polis" => $noPolisArray[$key],
                                        "periode_bayar" => $periodeBayarArray[$key],
                                        "total_periode" => $totalPeriodeArray[$key]
                                    ]
                                ]
                            ]);
                        }
            
                        $statusCode = $response->status();
                        if ($statusCode == 200) {
                            $responseBody = json_decode($response->getBody(), true);
                                $status = $responseBody['status'];
                                $message = '';
                                if ($status == "00") {
                                    // simpan ke db
                                    foreach ($premiArray as $key => $premi) {
                                        // db pembayaran premi
                                        $createPremi = new PembayaranPremi();
                                        $createPremi->asuransi_id = $idNoAplikasiArray[$key];
                                        $createPremi->nobukti_pembayaran = $noBuktiPembayaranArray[$key];
                                        $createPremi->tgl_bayar = $objekTanggal->format('Y-m-d');
                                        $createPremi->total_premi = $premi;
                                        $createPremi->save();
            
                                        
                                        // db pembayaran premi detail
                                        $createPremiDetail = new PembayaranPremiDetail();
                                        $createPremiDetail->pembayaran_premi_id = $createPremi->id;
                                        $createPremiDetail->no_rek = $noRekArray[$key];
                                        $createPremiDetail->no_pk = $noPkArray[$key];
                                        $createPremiDetail->periode_bayar = $periodeBayarArray[$key];
                                        $createPremiDetail->total_periode = $totalPeriodeArray[$key];
                                        $createPremiDetail->save();

                                    }
                                    $this->logActivity->store('Pengguna ' . $request->name . ' menambahkan '. $key+1 .' pembayaran premi.');
            
                                    $message = $responseBody['keterangan'];
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
            }else{
                Alert::warning('Warning!', 'Silahkan pilih no aplikasi terlebih dahulu');
                return back();
            }
        }else{
            Alert::warning('Warning!', 'Tanggal Bayar harus di pilih.');
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
