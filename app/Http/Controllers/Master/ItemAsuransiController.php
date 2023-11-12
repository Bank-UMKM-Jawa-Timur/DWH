<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MstFormItemAsuransi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemAsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param['title'] = 'Notifikasi Template';
        $param['pageTitle'] = 'Notifikasi Template';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;

        return view('pages.mst_form_system_asuransi.index', $param);
    }

    public function list($page_length =5, $searchQuery, $searchBy)
    {
        $data = MstFormItemAsuransi::orderBy('label');
        if ($searchQuery && $searchBy === 'field') {
            $data->where(function ($q) use ($searchQuery) {
                $q->where('label', 'like', '%' . $searchQuery . '%')
                ->orWhere('level', 'like', '%' . $searchQuery . '%')
                ->orWhere('parent_id', 'like', '%' . $searchQuery . '%')
                ->orWhere('type', 'like', '%' . $searchQuery . '%')
                ->orWhere('sequence', 'like', '%' . $searchQuery . '%')
                ->orWhere('only_accept', 'like', '%' . $searchQuery . '%');
            });
        }

        if (is_numeric($page_length))
            $data = $data->paginate($page_length);
        else
            $data = $data->get();
        return $data;
    }

    public function getField(){
        $data = MstFormItemAsuransi::orderBy('id', 'ASC')->get();
        return response()->json(['result' => $data]);
    }

    // public function search($req, $page_length = 5)
    // {
    //     $data = MstFormItemAsuransi::orderBy('notification_templates.id')
    //     ->where('tittle', 'LIKE', '%' . $req . '%')
    //         ->orWhere('content', 'LIKE', '%' . $req . '%');

    //     if (is_numeric($page_length))
    //         $data = $data->paginate($page_length);
    //     else
    //         $data = $data->get();
    //     return $data;
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dataField = MstFormItemAsuransi::orderBy('id', 'ASC')->get();
        // $data = response()->json(['result' => $dataField]);

        return view('pages.mst_form_system_asuransi.create', compact(['dataField']));
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
            'label' => 'required',
            'level' => 'required',
            'sequence' => 'required',
            'only_accept' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'label' => 'Label',
            'level' => 'Level',
            'sequence' => 'Sequence',
            'only_accept' => 'Only Accept',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $newItem =  new MstFormItemAsuransi();
            $newItem->label = $request->label;
            $newItem->level = $request->level;
            $newItem->parent_id = $request->parent_id;
            $newItem->type = $request->type;
            $newItem->formula = $request->formula;
            $newItem->sequence = $request->sequence;
            $newItem->only_accept = $request->only_accept;
            $newItem->have_default_value = '';
            $newItem->rupiah = $request->rupiah;
            $newItem->readonly = $request->readonly;
            $newItem->hidden = $request->hidden;
            $newItem->disabled = $request->disabled;
            $newItem->required = $request->required;
            $newItem->save();


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
