<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Alert;
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
            $page_length = $request->page_length ? $request->page_length : 5;
            $data = DB::table('asuransi');
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
            $data = $data->orderBy('no_aplikasi')->paginate($page_length);

            return view('pages.asuransi-registrasi.index', compact('data'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Terjadi kesalahan pada database. '.$e->getMessage());
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


    public function getJenisAsuransi($jenis_kredit){
        $dataAsuransi = DB::table('mst_jenis_asuransi')->where('jenis_kredit', $jenis_kredit)->get();

        return response()->json([
            'data' => $dataAsuransi
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
        return $request;
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
}
