<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Alert;
use Illuminate\Support\Facades\Auth;

class ImportKKBController extends Controller
{
    private $logActivity;

    function __construct()
    {
        $this->logActivity = new LogActivitesController;
    }

    public function index() {
        // retrieve from api
        $host = config('global.los_api_host');
        $apiURL = $host . '/kkb/get-cabang';

        $headers = [
            'token' => config('global.los_api_token')
        ];

        $responseBody = null;
        $params['cabang'] = [];

        try {
            $response = Http::withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);
            // input file path
            if ($responseBody) {
                if (is_array($responseBody)) {
                    $params['cabang'] = $responseBody;
                }
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // return $e->getMessage();
        }
        
        return view('pages.import_kkb.index', $params);
    }

    public function store(Request $request) {
        try {
            DB::beginTransaction();
            /**
             * Steps
             * 1. Declare request variable
             * 2. Insert to imported_data table
             * 3. Insert to kredits table
             * 4. Insert to kkb table
             * 5. Insert to data_po table
             * 6. Check if have keterangan, if exists insert to ket_imported_data table
             * 7. Check if have progress data, if exists insert to documents table
             * 8. Insert to log_activities table
             */
            // Declare request variable
            $req_kode_cabang = $request->kode_cabang;
            $req_nama_debitur = $request->nama_debitur;
            $req_tgl_po = $request->tgl_po;
            $req_merk_kendaraan = $request->merk_kendaraan;
            $req_tipe_kendaraan = $request->tipe_kendaraan;
            $req_tahun_kendaraan = $request->tahun_kendaraan;
            $req_warna_kendaraan = $request->warna_kendaraan;
            $req_qty_kendaraan = $request->qty_kendaraan;
            $req_harga_kendaraan = $request->harga_kendaraan;
            $req_nama_stnk = $request->nama_stnk;
            $req_nominal_realisasi = $request->nominal_realisasi;
            $req_nominal_imbal_jasa = $request->nominal_imbal_jasa;
            $req_nominal_dp = $request->nominal_dp;
            $req_tgl_realisasi = $request->tgl_realisasi;
            $req_tgl_pelunasan = $request->tgl_pelunasan;
            $req_tgl_penyerahan_unit = $request->tgl_penyerahan_unit;
            $req_tgl_penyerahan_stnk = $request->tgl_penyerahan_stnk;
            $req_tgl_penyerahan_bpkb = $request->tgl_penyerahan_bpkb;
            $req_tgl_penyerahan_polis = $request->tgl_penyerahan_polis;
            $req_bpkb_via_bjsc = $request->bpkb_via_bjsc;
            $req_polis_via_bjsc = $request->polis_via_bjsc;
            $req_tgl_pembayaran_imbal_jasa = $request->tgl_pembayaran_imbal_jasa;
            $req_nominal_pembayaran_imbal_jasa = $request->nominal_pembayaran_imbal_jasa;
            $req_keterangan = $request->keterangan;
            $current_time = date('Y-m-d H:i:s');
            // End Declare request variable
            
            for ($i=0; $i < count($req_nama_debitur); $i++) {
                // Insert to imported_data table
                $tgl_po = date('Y-m-d', strtotime(str_replace('/', '-', $req_tgl_po[$i])));
                $tgl_realisasi = date('Y-m-d', strtotime(str_replace('/', '-', $req_tgl_realisasi[$i])));
                $create_imported_data = DB::table('imported_data')
                                            ->insertGetId([
                                                'name' => $req_nama_debitur[$i],
                                                'tgl_po' => $tgl_po,
                                                'tgl_realisasi' => $tgl_realisasi,
                                                'created_at' => $current_time,
                                                'updated_at' => $current_time,
                                            ]);
                // End Insert to imported_data table

                // Insert to kredits table
                $create_kredit = DB::table('kredits')
                                    ->insertGetId([
                                        'imported_data_id' => $create_imported_data,
                                        'kode_cabang' => $req_kode_cabang,
                                        'created_at' => $current_time,
                                        'updated_at' => $current_time,
                                    ]);
                // End Insert to kredits table

                // Insert to kkb table
                $tgl_ketersediaan_unit = date('Y-m-d', strtotime($tgl_realisasi . ' -1 day')); // H-1 tanggal realisasi
                $create_kkb = DB::table('kkb')
                                ->insertGetId([
                                    'kredit_id' => $create_kredit,
                                    'tgl_ketersediaan_unit' => $tgl_ketersediaan_unit,
                                    'nominal_realisasi' => (int) $req_nominal_realisasi[$i],
                                    'nominal_dp' => (int) $req_nominal_dp[$i],
                                    'nominal_imbal_jasa' => (int) $req_nominal_imbal_jasa[$i],
                                    'nominal_pembayaran_imbal_jasa' => (int) $req_nominal_pembayaran_imbal_jasa[$i],
                                    'created_at' => $current_time,
                                    'updated_at' => $current_time,
                                ]);
                // End Insert to kkb table

                // Insert to data_po table
                $create_data_po = DB::table('data_po')
                                    ->insertGetId([
                                        'imported_data_id' => $create_imported_data,
                                        'merk' => $req_merk_kendaraan[$i],
                                        'tipe' => $req_tipe_kendaraan[$i],
                                        'tahun_kendaraan' => $req_tahun_kendaraan[$i],
                                        'warna' => $req_warna_kendaraan[$i],
                                        'jumlah' => $req_qty_kendaraan[$i] != '-' ? (int) $req_qty_kendaraan[$i] : null,
                                        'harga' => $req_harga_kendaraan[$i] ? (int) $req_harga_kendaraan[$i] : null,
                                        'created_at' => $current_time,
                                        'updated_at' => $current_time,
                                    ]);
                // End Insert to data_po table

                // Check if have keterangan, if exists insert to ket_imported_data table
                $ket = $req_keterangan[$i];
                for ($j=0; $j < count($ket); $j++) { 
                    if ($ket[$j]) {
                        DB::table('ket_imported_data')
                            ->insert([
                                'imported_data_id' => $create_imported_data,
                                'keterangan' => $ket[$j],
                                'created_at' => $current_time,
                                'updated_at' => $current_time,
                            ]);
                    }
                }
                // End Check if have keterangan, if exists insert to ket_imported_data table

                // Check if have progress data, if exists insert to documents table

                // Bukti Pembayaran
                $create_bukti_pembayaran = false;
                $req_tgl_realisasi[$i] = strtolower($req_tgl_realisasi[$i]) == 'belum' || strtolower($req_tgl_realisasi[$i]) == 'sudah' ? '-' : $req_tgl_realisasi[$i];
                if ($req_tgl_realisasi[$i] != '-') {
                    $tgl_bukti_pembayaran = date('Y-m-d', strtotime(str_replace('/', '-', $req_tgl_realisasi[$i])));
                    if ($req_tgl_realisasi[$i] != '-') {
                        $create_bukti_pembayaran = DB::table('documents')->insert([
                            'kredit_id' => $create_kredit,
                            'date' => $tgl_bukti_pembayaran,
                            'document_category_id' => 1,
                            'is_imported_data' => true,
                            'is_confirm' => true,
                            'created_at' => $current_time,
                            'updated_at' => $current_time,
                        ]);
                    }
                }

                // Penyerahan Unit
                $create_penyerahan_unit = false;
                $req_tgl_penyerahan_unit[$i] = strtolower($req_tgl_penyerahan_unit[$i]) == 'belum' || strtolower($req_tgl_penyerahan_unit[$i]) == 'sudah' ? '-' : $req_tgl_penyerahan_unit[$i];
                if ($req_tgl_penyerahan_unit[$i] != '-') {
                    $tgl_penyerahan_unit = date('Y-m-d', strtotime(str_replace('/', '-', $req_tgl_penyerahan_unit[$i])));
                    if ($req_tgl_penyerahan_unit[$i] != '-') {
                        $create_penyerahan_unit = DB::table('documents')->insert([
                            'kredit_id' => $create_kredit,
                            'date' => $tgl_penyerahan_unit,
                            'document_category_id' => 2,
                            'is_imported_data' => true,
                            'is_confirm' => true,
                            'created_at' => $current_time,
                            'updated_at' => $current_time,
                        ]);
                    }
                }

                // Penyerahan STNK
                $create_penyerahan_stnk = false;
                $req_tgl_penyerahan_stnk[$i] = strtolower($req_tgl_penyerahan_stnk[$i]) == 'belum' || strtolower($req_tgl_penyerahan_stnk[$i]) == 'sudah' ? '-' : $req_tgl_penyerahan_stnk[$i];
                if ($req_tgl_penyerahan_stnk[$i] != '-') {
                    $tgl_penyerahan_stnk = date('Y-m-d', strtotime(str_replace('/', '-', $req_tgl_penyerahan_stnk[$i])));
                    if ($req_tgl_penyerahan_stnk[$i] != '-'
                        && strtolower($req_tgl_penyerahan_stnk[$i]) != 'sudah'
                        && strtolower($req_tgl_penyerahan_stnk[$i]) != 'belum') {
                        $create_penyerahan_stnk = DB::table('documents')->insert([
                            'kredit_id' => $create_kredit,
                            'date' => $tgl_penyerahan_stnk,
                            'document_category_id' => 3,
                            'is_imported_data' => true,
                            'is_confirm' => true,
                            'created_at' => $current_time,
                            'updated_at' => $current_time,
                        ]);
                    }
                }

                // Penyerahan BPKB
                $create_penyerahan_bpkb = false;
                $req_tgl_penyerahan_bpkb[$i] = strtolower($req_tgl_penyerahan_bpkb[$i]) == 'belum' || strtolower($req_tgl_penyerahan_bpkb[$i]) == 'sudah' ? '-' : $req_tgl_penyerahan_bpkb[$i];
                if ($req_tgl_penyerahan_bpkb[$i] != '-') {
                    $tgl_penyerahan_bpkb = date('Y-m-d', strtotime(str_replace('/', '-', $req_tgl_penyerahan_bpkb[$i])));
                    if ($req_tgl_penyerahan_bpkb[$i] != '-'
                        && strtolower($req_tgl_penyerahan_bpkb[$i]) != 'sudah'
                        && strtolower($req_tgl_penyerahan_bpkb[$i]) != 'belum') {
                        $create_penyerahan_bpkb = DB::table('documents')->insert([
                            'kredit_id' => $create_kredit,
                            'date' => $tgl_penyerahan_bpkb,
                            'document_category_id' => 5,
                            'is_imported_data' => true,
                            'is_confirm' => true,
                            'created_at' => $current_time,
                            'updated_at' => $current_time,
                        ]);
                    }
                }

                // Penyerahan Polis
                $create_penyerahan_polis = false;
                $req_tgl_penyerahan_polis[$i] = strtolower($req_tgl_penyerahan_polis[$i]) == 'belum' || strtolower($req_tgl_penyerahan_polis[$i]) == 'sudah' ? '-' : $req_tgl_penyerahan_polis[$i];
                if ($req_tgl_penyerahan_polis[$i] != '-') {
                    $tgl_penyerahan_polis = date('Y-m-d', strtotime(str_replace('/', '-', $req_tgl_penyerahan_polis[$i])));
                    if ($req_tgl_penyerahan_polis[$i] != '-'
                        && strtolower($req_tgl_penyerahan_polis[$i]) != 'sudah'
                        && strtolower($req_tgl_penyerahan_polis[$i]) != 'belum') {
                        $create_penyerahan_polis = DB::table('documents')->insert([
                            'kredit_id' => $create_kredit,
                            'date' => $tgl_penyerahan_polis,
                            'document_category_id' => 4,
                            'is_imported_data' => true,
                            'is_confirm' => true,
                            'created_at' => $current_time,
                            'updated_at' => $current_time,
                        ]);
                    }
                }

                // Pembayaran Imbal Jasa
                $create_pembayaran_imbal_jasa = false;
                $req_tgl_pembayaran_imbal_jasa[$i] = strtolower($req_tgl_pembayaran_imbal_jasa[$i]) == 'belum' || strtolower($req_tgl_pembayaran_imbal_jasa[$i]) == 'sudah' ? '-' : $req_tgl_pembayaran_imbal_jasa[$i];
                if ($req_tgl_pembayaran_imbal_jasa[$i] != '-') {
                    $tgl_pembayaran_imbal_jasa = date('Y-m-d', strtotime(str_replace('/', '-', $req_tgl_pembayaran_imbal_jasa[$i])));
                    $create_pembayaran_imbal_jasa = DB::table('documents')->insert([
                        'kredit_id' => $create_kredit,
                        'date' => $tgl_pembayaran_imbal_jasa,
                        'document_category_id' => 6,
                        'is_imported_data' => true,
                        'is_confirm' => true,
                        'created_at' => $current_time,
                        'updated_at' => $current_time,
                    ]);
                }

                // Tagihan
                if ($create_bukti_pembayaran) {
                    $tgl_tagihan = date('Y-m-d', strtotime($tgl_bukti_pembayaran . ' -1 day')); // H-1 tanggal realisasi
                    DB::table('documents')->insert([
                        'kredit_id' => $create_kredit,
                        'date' => $tgl_tagihan,
                        'document_category_id' => 7,
                        'is_imported_data' => true,
                        'is_confirm' => true,
                        'created_at' => $current_time,
                        'updated_at' => $current_time,
                    ]);
                    }
                // End Check if have progress data, if exists insert to documents table
            }

            // Insert to log_activities table
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $name = $token ? $user['data']['nip'] : $user->email;

            $this->logActivity->store('Pengguna ' . $name . ' melakukan import data kkb.');
            // End Insert to log_activities table

            DB::commit();
            Alert::success('Sukses', 'Berhasil menyimpan data');

            return redirect()->route('kredit.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());

            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());

            return back();
        }
    }
}
