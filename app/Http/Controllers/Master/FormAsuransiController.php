<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MstFormAsuransi;
use App\Models\MstFormItemAsuransi;
use App\Models\MstPerusahaanAsuransi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormAsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param['title'] = 'Master List Form Asuransi';
        $param['pageTitle'] = 'Master List Form Asuransi';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;
        $param['data_perusahaan'] = MstPerusahaanAsuransi::select('id','nama')->orderBy('id', 'ASC')->get();
        $param['data_item'] = MstFormItemAsuransi::select('id','label')->orderBy('id', 'ASC')->get();

        return view('pages.mst_form_asuransi.index', $param);
    }

    public function list($page_length =5, $searchQuery, $searchBy)
    {
        $data = MstFormAsuransi::with(['perusahaanAsuransi','itemAsuransi'])->orderBy('id', 'ASC');
        if ($searchQuery && $searchBy === 'field') {
            $data->where(function ($q) use ($searchQuery) {
                $q->where('perusahaanAsuransi.nama', 'like', '%' . $searchQuery . '%')
                ->orWhere('itemAsuransi.label', 'like', '%' . $searchQuery . '%')
                ->orWhere('itemAsuransi.level', 'like', '%' . $searchQuery . '%')
                ->orWhere('itemAsuransi.type', 'like', '%' . $searchQuery . '%')
                ->orWhere('itemAsuransi.formula', 'like', '%' . $searchQuery . '%')
                ->orWhere('itemAsuransi.sequence', 'like', '%' . $searchQuery . '%')
                ->orWhere('itemAsuransi.only_accept', 'like', '%' . $searchQuery . '%');
            });
        }

        if (is_numeric($page_length))
            $data = $data->paginate($page_length);
            // $data = $data->paginate($page_length)->withQueryString();
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
            'perusahaan_id' => 'required',
            'form_item_asuransi_id' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'perusahaan_id' => 'Perusahaan Asuransi',
            'form_item_asuransi_id' => 'Item Asuransi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $newAsuransi =  new MstFormAsuransi();
            $newAsuransi->perusahaan_id = $request->perusahaan_id;
            $newAsuransi->form_item_asuransi_id = $request->form_item_asuransi_id;
            $newAsuransi->save();


            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan';
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
