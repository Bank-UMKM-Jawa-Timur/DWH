<?php

namespace App\Http\Controllers\Master;


use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\ImbalJasa;
use App\Models\TenorImbalJasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImbalJasaController extends Controller
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
        $param['title'] = 'Imbal Jasa';
        $param['pageTitle'] = 'Imbal Jasa';
        $page_length = $request->page_length ? $request->page_length : 5;
        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        return view('pages.imbal_jasa.index', $param);
    }

    public function list($page_length = 5)
    {
        $data = ImbalJasa::orderBy('plafond1');
        if (is_numeric($page_length))
            $data = $data->paginate($page_length);
        else
            $data = $data->get();
        return $data;
    }

    public function search($req, $page_length =5, $searchQuery, $searchBy)
    {
        $data = ImbalJasa::orderBy('plafond1');
        if ($searchQuery && $searchBy === 'field') {
            $data->where(function ($q) use ($searchQuery) {
                $q->where('plafond1' + 'plafond2', '=', $searchQuery);
            });
        }
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
            'plafond1' => 'required',
            'plafond2' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            'plafond1' => 'Plafond',
            'plafond2' => 'Plafond',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $model = new ImbalJasa();
            $model->plafond1 = str_replace('.','',$request->plafond1);
            $model->plafond2 = str_replace('.','',$request->plafond2);
            $model->save();

            foreach ($request->tenor as $key => $value) {
                $modelDetail = new TenorImbalJasa();
                $modelDetail->imbaljasa_id = $model->id;
                $modelDetail->tenor = $value;
                $modelDetail->imbaljasa = str_replace('.','',$request->imbaljasa[$key]);
                $modelDetail->save();
            }

            $this->logActivity->store("Membuat data imbal jasa.");

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } finally {
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
     * @param  \App\Models\ImbalJasa  $imbalJasa
     * @return \Illuminate\Http\Response
     */
    public function show(ImbalJasa $imbalJasa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ImbalJasa  $imbalJasa
     * @return \Illuminate\Http\Response
     */
    public function edit(ImbalJasa $imbalJasa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImbalJasa  $imbalJasa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImbalJasa $imbalJasa)
    {
        $status = '';
        $message = '';

        $validator = Validator::make($request->all(), [
            'plafond1' => 'required',
            'plafond2' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            'plafond1' => 'Plafond',
            'plafond2' => 'Plafond',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $imbalJasa->plafond1 = str_replace('.','',$request->plafond1);
            $imbalJasa->plafond2 = str_replace('.','',$request->plafond2);
            $imbalJasa->save();

            foreach ($request->id_imbaljasa as $key => $value) {
                // if ($value != null || $value != '' || !empty($value)) {
                $modelDetail = TenorImbalJasa::find($value);
                $modelDetail->imbaljasa = str_replace('.','',$request->imbaljasa[$key]);
                $modelDetail->save();
                // } else {
                //     $modelDetail = new TenorImbalJasa();
                //     $modelDetail->imbaljasa->id = 4;
                //     $modelDetail->tenor = $request->tenor[$key];
                //     $modelDetail->imbaljasa = $request->imbaljasa[$key];
                //     $modelDetail->save();
                // }
            }

            $this->logActivity->store("Mengupdate data imbal jasa.");

            $status = 'success';
            $message = 'Berhasil mengupdate data';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } finally {
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
     * @param  \App\Models\ImbalJasa  $imbalJasa
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImbalJasa $imbalJasa)
    {
        try {
            if ($imbalJasa) {
                TenorImbalJasa::where('imbaljasa_id', $imbalJasa->id)->delete();
                $imbalJasa->delete();
                $this->logActivity->store("Menghapus data imbal jasa.");

                $status = 'success';
                $message = 'Berhasil menghapus data.';
            } else {
                $status = 'error';
                $message = 'Data tidak ditemukan.';
            }
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan' . $e;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }
}
