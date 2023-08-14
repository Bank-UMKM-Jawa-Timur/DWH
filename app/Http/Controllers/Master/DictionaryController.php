<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\MstFileDictionary;
use Illuminate\Http\Request;

class DictionaryController extends Controller
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
        $param['title'] = 'Dictionary';
        $param['pageTitle'] = 'Dictionary';
        $page_length = $request->page_length ? $request->page_length : 5;

        if ($request->ajax()) {
            $data =$this->search($request->search, $page_length);
            return response()->json(['data'=>$data]);
        }
        else {
            $data = $this->list($page_length);
            $param['data'] = $data;
            return view('pages.dictionary.index', $param);
        }
    }

    public function list($page_length = 5)
    {
        $data = MstFileDictionary::orderBy('filename');
        if (is_numeric($page_length))
            $data = $data->paginate($page_length);
        else
            $data = $data->get();
        return $data;
    }

    public function search($req, $page_length = 5){
        $data = MstFileDictionary::orderBy('filename')
                                ->where('filename','LIKE','%'.$req.'%')
                                ->orWhere('description','LIKE','%'.$req.'%');

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
        $param['title'] = 'Tambah Dictionary';
        $param['pageTitle'] = 'Dictionary';
        return view('pages.dictionary.create', $param);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
