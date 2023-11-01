<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Utils\PaginateController;
use App\Models\Document;
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
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    private $role_id;
    private $param;


    function __construct()
    {
        $this->role_id = Session::get(config('global.role_id_session'));
    }

    public function index(Request $request)
    {
        /**
         * File path LOS
         *
         * upload/{id_pengajuan}/sppk/{filename}
         * upload/{id_pengajuan}/po/{filename}
         * upload/{id_pengajuan}/pk/{filename}
         */
        try {
            $total_target = 0;
            $target = Target::select('id', 'nominal', 'total_unit')->where('is_active', 1)->first();
            $this->param['target'] = $target;

            $data_realisasi = [];
            $total_terealisasi = 0;

            $all_data = Kredit::select(
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
                ->whereNotNull('kredits.pengajuan_id')
                ->whereNull('kredits.imported_data_id')
                ->get();
            foreach ($all_data as $key => $value) {
                if ($value->status == 'done')
                    $total_terealisasi++;
            }
            if ($target)
                $total_target = $target->total_unit;

            $this->param['total_belum_terealisasi'] = $total_target - $total_terealisasi;
            $this->param['total_terealisasi'] = $total_terealisasi;

            $notification = Notification::select('notifications.id', 'notifications.read', 'notifications.extra', 'notifications.created_at', 'temp.title', 'temp.content')
                ->join('users AS u', 'u.id', 'notifications.user_id')
                ->join('notification_templates AS temp', 'temp.id', 'notifications.template_id')
                ->where('u.id', \Session::get(config('global.user_id_session')))
                ->where('notifications.read', 0)
                ->orderBy('notifications.created_at', 'DESC')
                ->get();
            $this->param['notification'] = $notification;

            $this->param['role_id'] = \Session::get(config('global.role_id_session'));
            $this->param['staf_analisa_kredit_role'] = 'Staf Analis Kredit';
            $this->param['is_kredit_page'] = request()->is('kredit');
            $page_length = $request->page_length ? $request->page_length : 5;
            $page_length_import = $request->page_length_import ? $request->page_length_import : 5;
            $this->param['role'] = $this->getRoleName();
            $this->param['title'] = 'KKB';
            $this->param['pageTitle'] = 'KKB';
            $this->param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit', 'Bukti Pembayaran Imbal Jasa'])->orderBy('name', 'DESC')->get();
            $tab_type = $request->get('tab_type');
            $temp_page = $request->page;

            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $role = '';
            if ($user) {
                if (is_array($user)) {
                    $role = $user['role'];
                }
            }
            else {
                $role = 'vendor';
            }

            $user_id = $token ? $user['id'] : $user->id;
            $user_cabang = $token ? $user['kode_cabang'] : $user->kode_cabang;
            if (!$token)
                $user_id = 0; // vendor

            return view('pages.home', $this->param);
        } catch (\Exception $e) {
            return $e->getMessage();
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

    public function loadKreditById($pengajuan_id)
    {
        $data = Kredit::select(
            'kredits.id',
            \DB::raw("IF (kredits.pengajuan_id IS NOT NULL, 'data_kkb', 'data_import') AS kategori"),
            'kredits.pengajuan_id',
            'kredits.imported_data_id',
            'kredits.kode_cabang',
            'kkb.id AS kkb_id',
            'kkb.tgl_ketersediaan_unit',
            'kkb.id_tenor_imbal_jasa',
            'kkb.nominal_realisasi',
            'kkb.nominal_dp',
            'kkb.nominal_imbal_jasa',
            'kkb.nominal_pembayaran_imbal_jasa',
            'import.name',
            'import.tgl_po',
            'import.tgl_realisasi',
            'po.merk',
            'po.tipe',
            'po.tahun_kendaraan',
            'po.warna',
            'po.keterangan',
            'po.jumlah',
            'po.harga',
            \DB::raw("(SELECT COUNT(id) FROM document_categories) AS total_doc_requirement"),
            \DB::raw('COALESCE(COUNT(d.id), 0) AS total_file_uploaded'),
            \DB::raw('CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) AS total_file_confirmed'),
            \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < (SELECT COUNT(id) FROM document_categories), 'in progress', 'done') AS status"),
        )
            ->join('kkb', 'kkb.kredit_id', 'kredits.id')
            ->leftJoin('imported_data AS import', 'import.id', 'kredits.imported_data_id')
            ->leftJoin('data_po AS po', 'po.imported_data_id', 'kredits.imported_data_id')
            ->leftJoin('documents AS d', 'd.kredit_id', 'kredits.id')
            ->groupBy([
                'kredits.id',
                'kredits.pengajuan_id',
                'kredits.imported_data_id',
                'kredits.kode_cabang',
                'kkb.id_tenor_imbal_jasa',
                'kkb.id',
                'kkb.tgl_ketersediaan_unit',
                'po.merk',
                'po.tipe',
                'po.tahun_kendaraan',
                'po.warna',
                'po.keterangan',
                'po.jumlah',
                'po.harga',
            ])
            ->where('kredits.pengajuan_id', $pengajuan_id)
            ->first();

        if ($data) {
            $invoice = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 7)
                ->first();

            $buktiPembayaran = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 1)
                ->first();

            $penyerahanUnit = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 2)
                ->first();

            $stnk = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 3)
                ->first();

            $polis = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 4)
                ->first();

            $bpkb = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 5)
                ->first();

            $imbalJasa = Document::where('kredit_id', $data->id)
                ->where('document_category_id', 6)
                ->first();

            $setImbalJasa = DB::table('tenor_imbal_jasas')->find($data->id_tenor_imbal_jasa);

            $data->invoice = $invoice;
            $data->bukti_pembayaran = $buktiPembayaran;
            $data->penyerahan_unit = $penyerahanUnit;
            $data->stnk = $stnk;
            $data->bpkb = $bpkb;
            $data->polis = $polis;
            $data->imbal_jasa = $imbalJasa;
            $data->set_imbal_jasa = $setImbalJasa;
        }

        return $data;
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function getRoleName()
    {
        $token = \Session::get(config('global.user_token_session'));
        $user = User::select(
            'users.id',
            'users.role_id',
            'r.name AS role_name',
        )
            ->join('roles AS r', 'r.id', 'users.role_id')
            ->where('users.id', $token ? \Session::get(config('global.user_id_session')) : Auth::user()->id)
            ->first();

        return $user ? $user->role_name : '';
    }

    public function getChartData()
    {
        $host = env('LOS_API_HOST');
        $headers = [
            'token' => env('LOS_API_TOKEN')
        ];

        $apiCabang = $host . '/kkb/get-cabang/';
        $api_req = Http::timeout(6)->withHeaders($headers)->get($apiCabang);
        $responseCabang = json_decode($api_req->getBody(), true);
        $dataCharts = [];

        if ($responseCabang) {
            // for ($i = 0; $i < count($responseCabang); $i++) {
            foreach ($responseCabang as $key => $value) {
                $kode_cabang = $value['kode_cabang'];
                $cabang = $value['cabang'];
                $dataKredits = Kredit::select(
                    'kredits.id',
                    'kredits.pengajuan_id',
                    'kredits.kode_cabang',
                    'kkb.id AS kkb_id',
                    'kkb.tgl_ketersediaan_unit',
                    'kkb.id_tenor_imbal_jasa',
                    \DB::raw("(SELECT COUNT(id) FROM document_categories) AS total_doc_requirement"),
                    \DB::raw('COALESCE(COUNT(d.id), 0) AS total_file_uploaded'),
                    \DB::raw('CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) AS total_file_confirmed'),
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
                    ->whereNotNull('kredits.pengajuan_id')
                    ->whereNull('kredits.imported_data_id')
                    ->having("status", 'done')
                    ->where('kode_cabang', $kode_cabang)
                    ->count();
                $dataImported = DB::table('imported_data AS import')
                    ->select(
                        'import.name',
                        'import.tgl_po',
                        'import.tgl_realisasi',
                        'kredits.id',
                        'kredits.pengajuan_id',
                        'kredits.imported_data_id',
                        'kredits.kode_cabang',
                        'kkb.id AS kkb_id',
                        'kkb.user_id',
                        'kkb.tgl_ketersediaan_unit',
                        'kkb.id_tenor_imbal_jasa',
                        'kkb.nominal_realisasi',
                        'kkb.nominal_dp',
                        'kkb.nominal_imbal_jasa',
                        'kkb.nominal_pembayaran_imbal_jasa',
                        'po.merk',
                        'po.tipe',
                        'po.tahun_kendaraan',
                        'po.warna',
                        'po.keterangan',
                        'po.jumlah',
                        'po.harga',
                        \DB::raw("(SELECT COUNT(id) FROM document_categories) AS total_doc_requirement"),
                        \DB::raw('COALESCE(COUNT(d.id), 0) AS total_file_uploaded'),
                        \DB::raw('CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) AS total_file_confirmed'),
                        \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < (SELECT COUNT(id) FROM document_categories), 'in progress', 'done') AS status"),
                    )
                    ->join('kredits', 'kredits.imported_data_id', 'import.id')
                    ->join('kkb', 'kkb.kredit_id', 'kredits.id')
                    ->join('data_po AS po', 'po.imported_data_id', 'kredits.imported_data_id')
                    ->leftJoin('documents AS d', 'd.kredit_id', 'kredits.id')
                    ->having("status", 'done')
                    ->where('kode_cabang', $kode_cabang)
                    ->groupBy([
                        'kredits.id',
                        'kredits.imported_data_id',
                        'kredits.kode_cabang',
                        'kkb.id_tenor_imbal_jasa',
                        'kkb.id',
                        'kkb.tgl_ketersediaan_unit',
                        'po.merk',
                        'po.tipe',
                        'po.tahun_kendaraan',
                        'po.warna',
                        'po.keterangan',
                        'po.jumlah',
                        'po.harga',
                    ])
                    ->count();

                $dataCabang = [
                    'kode_cabang' => $kode_cabang,
                    'cabang' => $cabang,
                    'total' => intval($dataKredits),
                ];

                array_push($dataCharts, $dataCabang);
            }

            return response()->json([
                'data' => $dataCharts
            ]);
        } else {
            return response()->json([
                'data' => null
            ]);
        }
    }
}
