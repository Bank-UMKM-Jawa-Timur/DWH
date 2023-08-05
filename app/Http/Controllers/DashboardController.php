<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Master\PenggunaController;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kredit;
use App\Models\Notification;
use App\Models\Target;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $page_length = $request->page_length ? $request->page_length : 5;
            $param['title'] = 'Dashboard';
            $param['pageTitle'] = 'Dashboard';
            $param['karyawan'] = null;
            $user = User::select(
                'users.id',
                'users.nip',
                'users.role_id',
                'r.name AS role_name',
            )
                ->join('roles AS r', 'r.id', 'users.role_id')
                ->where('users.id', Auth::user()->id)
                ->first();

            $param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit', 'Bukti Pembayaran Imbal Jasa'])->orderBy('name', 'DESC')->get();
            $data = Kredit::select(
                'kredits.id',
                'kredits.pengajuan_id',
                'kredits.kode_cabang',
                'kkb.id AS kkb_id',
                'kkb.tgl_ketersediaan_unit',
                'kkb.id_tenor_imbal_jasa',
                \DB::raw("(SELECT COUNT(id) FROM document_categories) AS total_doc_requirement"),
                \DB::raw('COALESCE(COUNT(d.id), 0) AS total_file_uploaded'),
                \DB::raw('CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) AS total_file_confirmed'),
                // \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < COALESCE(COUNT(d.id), 0), 'process', 'done') AS status"),
                \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < (SELECT COUNT(id) FROM document_categories), 'in progress', 'done') AS status"),
            )
                ->join('kkb', 'kkb.kredit_id', 'kredits.id')
                ->leftJoin('documents AS d', 'd.kredit_id', 'kredits.id')
                ->groupBy([
                    'kredits.id',
                    'kredits.pengajuan_id',
                    'kredits.kode_cabang',
                    'kkb.id_tenor_imbal_jasa',
                    'kkb.id',
                    'kkb.tgl_ketersediaan_unit',
                ])
                ->when($request->tAwal && $request->tAkhir, function ($query) use ($request) {
                    return $query->whereBetween('kkb.tgl_ketersediaan_unit', [$request->tAwal, $request->tAkhir]);
                })
                ->when($request->cabang,function($query,$cbg){
                    return $query->where('kredits.kode_cabang',$cbg);
                })
                ->orderBy('total_file_uploaded')
                ->orderBy('total_file_confirmed');

            if (Auth::user()->role_id == 2) {
                $data->where('kredits.kode_cabang', Auth::user()->kode_cabang);
            }

            $data = $data->paginate($page_length);

            foreach ($data as $key => $value) {
                // retrieve from api
                $host = env('LOS_API_HOST');
                $apiURL = $host . '/kkb/get-data-pengajuan/' . $value->pengajuan_id;

                $headers = [
                    'token' => env('LOS_API_TOKEN')
                ];

                try {
                    $response = Http::timeout(3)->withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

                    $statusCode = $response->status();
                    $responseBody = json_decode($response->getBody(), true);
                    // input file path
                    if ($responseBody) {
                        if (array_key_exists('sppk', $responseBody))
                            $responseBody['sppk'] = "/upload/$value->pengajuan_id/sppk/" . $responseBody['sppk'];
                        if (array_key_exists('po', $responseBody))
                            $responseBody['po'] = "/upload/$value->pengajuan_id/po/" . $responseBody['po'];
                        if (array_key_exists('pk', $responseBody))
                            $responseBody['pk'] = "/upload/$value->pengajuan_id/pk/" . $responseBody['pk'];
                    }

                    // insert response to object
                    $value->detail = $responseBody;
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // return $e->getMessage();
                }
            }

            $penggunaController = new PenggunaController;
            foreach ($data as $key => $value) {
                if ($value->nip) {
                    $karyawan = $penggunaController->getKaryawan($value->nip);
                    if (gettype($karyawan) == 'string') {
                        $value->detail = null;
                    }
                    else {
                        if ($karyawan) {
                            if (array_key_exists('nama', $karyawan))
                                $value->detail = $karyawan;
                            else
                                $value->detail = null;
                        }
                    }
                }
            }

            $param['data'] = $data;

            $param['role'] = $user->role_name;
            $param['total_cabang'] = User::where('role_id', 2)->count();
            $param['total_vendor'] = User::where('role_id', 3)->count();
            $target = Target::where('is_active', 1)->pluck('total_unit');
            $param['target'] = count($target) ? $target->first() : 0;
            if (Auth::user()->role_id != 3) {
                $param['total_kkb_done'] = Kredit::join('documents AS d', 'd.kredit_id', 'kredits.id')
                    ->where('d.document_category_id', 2)
                    ->count();
                $param['total_pengguna'] = User::count();
            }

            if (Auth::user()->role_id == 2) {
                $param['notification'] = Notification::select(
                    'notifications.id',
                    'notifications.user_id',
                    'notifications.extra',
                    'notifications.read',
                    'notifications.created_at',
                    'notifications.updated_at',
                    'nt.title',
                    'nt.content',
                    'nt.action_id',
                    'nt.role_id',
                )
                    ->join('notification_templates AS nt', 'nt.id', 'notifications.template_id')
                    ->where('notifications.user_id', Auth::user()->id)
                    ->where('notifications.read', false)
                    ->orderBy('notifications.read')
                    ->orderBy('notifications.created_at', 'DESC')
                    ->get();
            }

            $arrLabelChartLabel = [];
            $arrBarChartData = [];
            $barChart = DB::select('SELECT COUNT(*) as total, k.kode_cabang FROM documents as d JOIN kredits as k ON k.id = d.kredit_id WHERE d.document_category_id = 1 AND d.is_confirm = true GROUP BY k.kode_cabang');
            foreach ($barChart as $k => $v) {
                $cabang = $v->kode_cabang;
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => env('LOS_API_HOST').'/kkb/get-cabang/'.$cabang,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'token: gTWx1U1bVhtz9h51cRNoiluuBfsHqty5MCdXRdmWthFDo9RMhHgHIwrU9DBFVaNj'
                ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $res = json_decode($response);
                $namaCabang = 'undifined';
                if ($res)
                    $namaCabang = $res->cabang;

                array_push($arrLabelChartLabel, $namaCabang);
                array_push($arrBarChartData, $v->total);
            }
            $param['barChartData'] = $arrBarChartData;
            $param['barChartLabel'] = $arrLabelChartLabel;

            $data_array = [];
            if($request->status != null){
                foreach($data as $rows){
                    if($rows->status == $request->status){
                        array_push($data_array,$rows);
                    }
                }
                $param['data'] = $this->paginate($data_array);
            }else{
                $param['data'] = $data;
            }

            $search_q = strtolower($request->get('query'));
            if ($search_q) {
                foreach ($data as $key => $value) {
                    $exists = 0;
                    if ($value->detail) {
                        if ($value->detail['nama']) {
                            if (str_contains(strtolower($value->detail['nama']), $search_q)) {
                                $exists++;
                            }
                        }
                        if ($value->detail['no_po']) {
                            if (str_contains(strtolower($value->detail['no_po']), $search_q)) {
                                $exists++;
                            }
                        }
                    }
                    if ($exists == 0)
                        unset($data[$key]); // remove data
                }
            }
            $param['data'] = $data;

            return view('pages.home', $param);
        } catch (\Exception $e) {
            return $e->getMessage();
            return redirect('/dashboard')->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/dashboard')->withError('Terjadi kesalahan pada database');
        }
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function getRoleName()
    {
        $user = User::select(
            'users.id',
            'users.role_id',
            'r.name AS role_name',
        )
            ->join('roles AS r', 'r.id', 'users.role_id')
            ->where('users.id', Auth::user()->id)
            ->first();

        return $user->role_name;
    }
}
