<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Kredit;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class KreditController extends Controller
{
    public function store(Request $request)
    {
        $status = '';
        $req_status = 0;
        $message = '';

        DB::beginTransaction();
        try {
            $req = $request->all();
            $fields = Validator::make($req, [
                'pengajuan_id' => ['required'],
                'kode_cabang' => ['required'],
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
