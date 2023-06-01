<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\KKB;
use App\Models\Kredit;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class KreditController extends Controller
{
    private $notificationController;

    function __construct()
    {
        $this->notificationController = new NotificationController;
    }

    public function store(Request $request)
    {
        $status = '';
        $req_status = 0;
        $message = '';
        $isUnique = '';

        DB::beginTransaction();
        try {
            $req = $request->all();
            if ($request->pengajuan_id && $request->kode_cabang) {
                $kredit = Kredit::where('pengajuan_id', $request->pengajuan_id)->where('kode_cabang', $request->kode_cabang)->first();
                if ($kredit)
                    $isUnique = 'unique:kredits,pengajuan_id';
            }

            $fields = Validator::make($req, [
                'pengajuan_id' => ['required', $isUnique],
                'nomor_pengajuan' => ['required'],
                'kode_cabang' => ['required'],
            ], [
                'required' => 'Atribut ini harus diisi.',
                'unique' => 'Atribut ini telah digunakan.',
            ]);

            if ($fields->fails()) {
                $req_status = HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY;
                $status = 'failed';
                $message = $fields->errors();
            } else {
                $tenor = $request->tenor < 36 ? $request->tenor : 36;
                $harga_kendaraan1 = $request->harga_kendaraan;
                $harga_kendaraan2 = $request->harga_kendaraan > 50000000 ?   0 : $request->harga_kendaraan;
                $setImbalJasa = DB::table('imbal_jasas')
                    ->join('tenor_imbal_jasas as ti', 'ti.imbaljasa_id', 'imbal_jasas.id')
                    ->select('ti.*');
                if ($request->harga_kendaraan > 50000000) {
                    $setImbalJasa = $setImbalJasa->where('plafond1', '<', $harga_kendaraan1)
                        ->where('plafond2', '=', $harga_kendaraan2);
                } else {
                    $setImbalJasa = $setImbalJasa->where('plafond1', '<', $harga_kendaraan1)
                        ->where('plafond2', '<', $harga_kendaraan2);
                }
                $setImbalJasa = $setImbalJasa->where('tenor', $tenor)
                    ->first();

                $model = new Kredit();
                $model->pengajuan_id = $request->pengajuan_id;
                $model->kode_cabang = $request->kode_cabang;
                $model->save();

                $createKKB = new KKB();
                $createKKB->kredit_id = $model->id;
                $createKKB->id_tenor_imbal_jasa = $setImbalJasa->id;
                $createKKB->save();

                // send notification
                $extraMessage = view('notifications.detail-notif')->with('nomor', $request->nomor_pengajuan)->render();
                $this->notificationController->sendWithExtra(2, $model->id, $extraMessage);

                $req_status = HttpFoundationResponse::HTTP_OK;
                $status = 'success';
                $message = 'Data saved successfully';
            }
        } catch (Exception $e) {
            DB::rollBack();
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan : ' . $e->getMessage();
        } catch (QueryException $e) {
            DB::rollBack();
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database: ' . $e->getMessage();
        } finally {
            DB::commit();
            return response()->json([
                'status' => $status,
                'message' => $message,
            ], $req_status);
        }
    }
}
