<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\LogActivitesController;
use App\Http\Controllers\Controller;
use App\Models\RatePremi;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BakiDebetController extends Controller
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
        $param['title'] = 'Rate Premi Baki Debet';
        $param['pageTitle'] = 'Rate Premi Baki Debet';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $param['page_length'] = $page_length;

        if ($request->ajax()) {
            $data = $this->list($page_length, $searchQuery, $searchBy);
            return response()->json(['data' => $data]);
        } else {
            $data = $this->list($page_length, $searchQuery, $searchBy);
            $param['data'] = $data;
            return view('pages.baki_debet.index', $param);
        }
    }

    public function list($page_length = 5, $searchQuery, $searchBy)
    {
        $data = RatePremi::where('jenis', 'bade')->orderBy('masa_asuransi1');

        if ($searchQuery && $searchBy === 'field') {
            $data->where(function ($q) use ($searchQuery) {
                $q->where('masa_asuransi1', '=', $searchQuery)
                    ->orWhere('rate', '=', $searchQuery);
            });
        }

        if (is_numeric($page_length))
            $data = $data->paginate($page_length);
        else
            $data = $data->get();
        return $data;
    }

    public function search($req, $page_length = 5)
    {
        $data = RatePremi::orderBy('masa_asuransi1')
            ->where('masa_asuransi1', 'LIKE', '%' . $req . '%')
            ->orWhere('rate', 'LIKE', '%' . $req . '%');

        if (is_numeric($page_length))
            $data = $data->paginate($page_length);
        else
            $data = $data->get();

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

            $addDataBakiDebet = new RatePremi();
            $addDataBakiDebet->masa_asuransi1 = $request->masa_asuransi1;
            $addDataBakiDebet->masa_asuransi2 = $request->masa_asuransi2 ? $request->masa_asuransi2 : 0;
            $addDataBakiDebet->jenis = $request->jenis;
            $addDataBakiDebet->rate = $request->rate;
            $addDataBakiDebet->save();

            $this->logActivity->store("Membuat data Rate Premi Baki Debet $request->jenis.");

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
            $asuransi2 = '';
            if ($request->masa_asuransi2 != null) {
                $asuransi2 = $request->masa_asuransi2;
            } else {
                $asuransi2 = 0;
            }


            $updateDataBekiDebet = RatePremi::find($id);
            $updateDataBekiDebet->masa_asuransi1 = $request->masa_asuransi1;
            $updateDataBekiDebet->masa_asuransi2 = $asuransi2;
            $updateDataBekiDebet->rate = $request->rate;
            $updateDataBekiDebet->save();

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

            $premi = RatePremi::findOrFail($id);
            $currentName = $premi->jenis;
            if ($premi) {
                $premi->delete();
                $this->logActivity->store("Menghapus data Rate Premi Baki Debet '$currentName'.");

                $status = 'success';
                $message = 'Berhasil menghapus data.';
            } else {
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
