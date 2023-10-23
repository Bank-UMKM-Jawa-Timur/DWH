<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\RatePremi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlafonController extends Controller
{
    private $logActivity;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param['title'] = 'Plafon';
        $param['pageTitle'] = 'Plafon';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;

        return view('pages.plafon.index', $param);
    }

    public function list($page_length = 5 , $searchQuery, $searchBy)
    {
        $query = RatePremi::where('jenis', 'plafon')->orderBy('masa_asuransi1');
        if ($searchQuery && $searchBy === 'field') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('masa_asuransi1', 'LIKE', "%$searchQuery%")
                    ->orWhere('rate', 'LIKE', "%$searchQuery%");
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = '';
        $message = '';

        $validator = Validator::make($request->all(), [
            'masa_asuransi1' => 'required',
            'jenis' => 'required',
            'rate' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            'masa_asuransi1' => 'Masa Asuransi Awal Bulan',
            'jenis' => 'Jenis',
            'rate' => 'Rate',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            DB::beginTransaction();

            $addDataPlafon = new RatePremi();
            $addDataPlafon->masa_asuransi1 = $request->masa_asuransi1;
            $addDataPlafon->masa_asuransi2 = $request->masa_asuransi2 ? $request->masa_asuransi2 : 0;
            $addDataPlafon->jenis = $request->jenis;
            $addDataPlafon->rate = $request->rate;
            $addDataPlafon->save();

            $this->logActivity->store("Membuat data Rate Premi Plafon $request->jenis.");

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan';
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } finally {
            DB::commit();
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
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
        $status = '';
        $message = '';

        $validator = Validator::make($request->all(), [
            'masa_asuransi1' => 'required',
            'rate' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            'masa_asuransi1' => 'Masa Asuransi Awal Bulan',
            'rate' => 'Rate',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            DB::beginTransaction();
            // $asuransi2 = '';
            // if ($request->masa_asuransi2 > 0) {
            //     $asuransi2 = $request->masa_asuransi2;
            // } else {
            //     $asuransi2 = 0;
            // }


            $updateDataPlafon = RatePremi::find($id);
            $updateDataPlafon->masa_asuransi1 = $request->masa_asuransi1;
            $updateDataPlafon->masa_asuransi2 = $request->masa_asuransi2 > 0 ? $request->masa_asuransi2 : 0 ;
            $updateDataPlafon->rate = $request->rate;
            $updateDataPlafon->save();

            $this->logActivity->store("Memperbarui data Rate Premi Baki Debet $request->jenis.");

            $status = 'success';
            $message = 'Berhasil menyimpan perubahan';
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan';
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } finally {
            DB::commit();
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = '';
        $message = '';

        try {
            DB::beginTransaction();

            $currentPlafon = RatePremi::findOrFail($id);
            $currentName = $currentPlafon->jenis;
            if ($currentPlafon) {
                $currentPlafon->delete();
                $this->logActivity->store("Menghapus data Rate Premi Plafon '$currentName'.");

                $status = 'success';
                $message = 'Berhasil menghapus data.';
            }
            else {
                $status = 'error';
                $message = 'Data tidak ditemukan.';
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'error';
            $message = 'Terjadi kesalahan.';

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $status = 'error';
            $message = 'Terjadi kesalahan pada database.';
        } finally {
            DB::commit();
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }
}
