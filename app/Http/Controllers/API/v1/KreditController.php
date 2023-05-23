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

    function __construct() {
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
                $model = new Kredit();
                $model->pengajuan_id = $request->pengajuan_id;
                $model->kode_cabang = $request->kode_cabang;
                $model->save();

                $createKKB = new KKB();
                $createKKB->kredit_id = $model->id;
                $createKKB->save();

                // send notification
                $extraMessage = view('notifications.detail-notif')->render();
                $this->notificationController->sendWithExtra(2, $extraMessage);

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
