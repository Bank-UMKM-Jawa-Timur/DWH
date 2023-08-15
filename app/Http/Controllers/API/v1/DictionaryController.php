<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\MstFileContentDictionary;
use App\Models\MstFileDictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class DictionaryController extends Controller
{
    public function index(Request $request) {
        $status = '';
        $req_status = 0;
        $message = '';
        $data = null;
        try {
            $fields = Validator::make($request->all(), [
                'filename' => ['required'],
            ], [
                'required' => 'Atribut ini harus diisi.',
            ]);

            if ($fields->fails()) {
                $req_status = HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY;
                $status = 'failed';
                $message = $fields->errors();
            }
            else {
                $fileDictionary = MstFileDictionary::select('id',
                                                            'filename',
                                                            'description'
                                                            )
                                                            ->where('filename', $request->filename)
                                                            ->first();
                if ($fileDictionary) {
                    $itemDictionary = MstFileContentDictionary::select('id',
                                                                        'field',
                                                                        'from',
                                                                        'to',
                                                                        'length',
                                                                        'description'
                                                                        )
                                                                        ->where('file_dictionary_id', $fileDictionary->id)
                                                                        ->orderBy('id')
                                                                        ->get();
                }
    
                $data = [
                    'id' => $fileDictionary->id,
                    'filename' => $fileDictionary->filename,
                    'description' => $fileDictionary->description,
                    'total_item' => count($itemDictionary),
                    'item' => $itemDictionary,
                ];
                $req_status = HttpFoundationResponse::HTTP_OK;
                $status = 'success';
                $message = 'Dictionary data successfully retrieved';
            }
        } catch (\Exception $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan : ' . $e->getMessage();
        } catch (\Illuminate\Database\QueryException $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database: ' . $e->getMessage();
        } finally {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $req_status);
        }
    }
}
