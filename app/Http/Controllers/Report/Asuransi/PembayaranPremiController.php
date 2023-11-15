<?php

namespace App\Http\Controllers\Report\Asuransi;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class PembayaranPremiController extends Controller
{
    private $losHeaders;
    private $losHost;

    function __construct() {
        $bearerToken = \Session::get(config('global.user_token_session'));
        $this->losHost = config('global.los_api_host');
        $this->losHeaders = [
            'token' => config('global.los_api_token')
        ];
    }

    public function index(Request $request) {
        ini_set('max_execution_time', 120);
        try {
            $page_length = $request->page_length ? $request->page_length : 5;
            $allCabang = $this->getAllCabang();

            if ($allCabang['status'] == 'berhasil') {
                $allCabang = $allCabang['data'];
            }

            $data = null;
            $d = $this->list($request, $page_length);

            $data = [
                'cabang' => $allCabang,
            ];

            $data = array_merge($data, $d);

            // return $data;
            return view('pages.report.asuransi.pembayaran.pembayaran', $data);
        } catch (Exception $e) {
            // Alert::error('Terjadi kesalahan', $e->getMessage());
            return $e->getMessage();
            return back();
        }
    }

    private function list(Request $request, $page_length) {
        $staf = null;
        $status = 'all';
        if ($request->has('status')) {
            $status = $request->status;
        }
        $kode_cabang = 'all';
        if ($request->has('cabang')) {
            $kode_cabang = $request->cabang;
            if ($request->cabang != 'all') {
                $staf = $this->getStafByCabang($request->cabang);
            }
        }
        $user_id = 'all';
        if ($request->has('nip')) {
            $user_id = $request->nip;
        }

        $data = DB::table('pembayaran_premi_detail AS d')
                ->join('pembayaran_premi AS pem', 'pem.id', 'd.pembayaran_premi_id')
                ->join('asuransi AS a', 'a.id', 'd.asuransi_id')
                ->join('asuransi_detail AS ad', 'ad.asuransi_id', 'a.id')
                ->join('kredits AS k', 'k.id', 'a.kredit_id')
                ->join('mst_jenis_asuransi', 'mst_jenis_asuransi.id', 'a.jenis_asuransi_id')
                ->join('mst_perusahaan_asuransi AS p', 'p.id', 'a.perusahaan_asuransi_id')
                ->select(
                    'k.pengajuan_id',
                    'p.nama AS perusahaan',
                    'pem.nobukti_pembayaran',
                    'pem.tgl_bayar',
                    'd.no_rek',
                    'a.nama_debitur',
                    'a.no_pk',
                    'a.no_aplikasi',
                    'a.premi',
                    'a.status',
                    'a.is_paid',
                    'ad.premi_disetor',
                    'd.periode_bayar',
                    'd.total_periode',
                    'k.kode_cabang',
                );

        if ($request->has('dari') && $request->has('sampai')) {
            $tAwal = date('Y-m-d', strtotime($request->get('dari')));
            $tAkhir = date('Y-m-d', strtotime($request->get('sampai')));

            $data = $data->when($status, function($query) use ($status) {
                            if ($status == '1') {
                                $query->where('is_paid', 1);
                            }
                            else {
                                $query->where('is_paid', 0);
                            }
                        })
                        ->where('k.is_asuransi', true)
                        ->whereBetween('pem.tgl_bayar', [$tAwal, $tAkhir])
                        ->when($kode_cabang, function ($query) use ($kode_cabang) {
                            if ($kode_cabang != 'all')
                                $query->where('k.kode_cabang', $kode_cabang);
                        })
                        ->when($user_id, function ($query) use ($user_id) {
                            if ($user_id != 'all')
                                $query->where('a.user_id', $user_id);
                        })
                        ->orWhereBetween('pem.tgl_bayar', [$tAwal, $tAkhir])
                        ->when($status, function($query) use ($status) {
                            if ($status == '1') {
                                $query->where('is_paid', 1);
                            } else {
                                $query->where('is_paid', 0);
                            }
                        })
                        ->where('k.is_asuransi', true)
                        ->when($kode_cabang, function ($query) use ($kode_cabang) {
                            if ($kode_cabang != 'all')
                                $query->where('k.kode_cabang', $kode_cabang);
                        })
                        ->when($user_id, function ($query) use ($user_id) {
                            if ($user_id != 'all')
                                $query->where('a.user_id', $user_id);
                        });
        }
        else {
            $month = date('m');
            $data = $data->when($status, function($query) use ($status) {
                            if ($status != 'all') {
                                $query->where('is_paid', $status);
                            }
                        })
                        ->where('k.is_asuransi', true)
                        ->whereMonth('pem.tgl_bayar', $month)
                        ->when($kode_cabang, function ($query) use ($kode_cabang) {
                            if ($kode_cabang != 'all')
                                $query->where('k.kode_cabang', $kode_cabang);
                        })
                        ->when($user_id, function ($query) use ($user_id) {
                            if ($user_id != 'all')
                                $query->where('a.user_id', $user_id);
                        })
                        ->orWhereMonth('pem.tgl_bayar', $month)
                        ->when($status, function($query) use ($status) {
                            if ($status != 'all') {
                                $query->where('is_paid', $status);
                            }
                        })
                        ->where('k.is_asuransi', true)
                        ->when($kode_cabang, function ($query) use ($kode_cabang) {
                            if ($kode_cabang != 'all')
                                $query->where('k.kode_cabang', $kode_cabang);
                        })
                        ->when($user_id, function ($query) use ($user_id) {
                            if ($user_id != 'all')
                                $query->where('a.user_id', $user_id);
                        });
        }

        if ($request->has('q')) {
            $q = $request->get('q');
            $data = $data->when($q, function($query) use ($q) {
                        $query->where('a.nama_debitur', 'LIKE', "%$q%")
                            ->orWhere('a.no_aplikasi', 'LIKE', "%$q%")
                            ->orWhere('a.no_pk', 'LIKE', "%$q%")
                            ->orWhere('pem.tgl_bayar', 'LIKE', "%$q%")
                            ->orWhere('a.no_rek', 'LIKE', "%$q%");
                    });
        }

        if (is_numeric($page_length)) {
            $data = $data->orderBy('pem.tgl_bayar', 'DESC')->paginate($page_length);
        } else {
            $data = $data->orderBy('pem.tgl_bayar', 'DESC')->get();
        }

        $token = \Session::get(config('global.user_token_session'));
        $this->losHeaders['Authorization'] = "Bearer $token";

        foreach ($data as $key => $value) {
            $apiPengajuan = $this->losHost . '/v1/get-list-pengajuan-by-id/' . $value->pengajuan_id;
            $api_req = Http::timeout(6)->withHeaders($this->losHeaders)->get($apiPengajuan);
            $response = json_decode($api_req->getBody(), true);
            $pengajuan = null;
            if ($response) {
                if (array_key_exists('status', $response)) {
                    if ($response['status'] == 'success') {
                        $pengajuan = $response['data'];
                    }
                }
            }
            $value->pengajuan = $pengajuan;
        }

        return [
            'staf' => $staf,
            'pembayaran' => $data,
        ];
    }
}
