<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\MstFileDictionary;
use Illuminate\Http\Request;
use Alert;
use App\Models\MstFileContentDictionary;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();
            $newFileDictionary = new MstFileDictionary;
            $newFileDictionary->filename = $request->filename;
            $newFileDictionary->description = $request->description;
            $newFileDictionary->save();
            $file_dictionary_id = $newFileDictionary->id;

            $input_field = $request->get('input_field');
            $input_from = $request->get('input_from');
            $input_to = $request->get('input_to');
            $input_length = $request->get('input_length');
            $input_description = $request->get('input_description');

            if ($input_field) {
                if (is_array($input_field)) {
                    for ($i=0; $i < count($input_field); $i++) { 
                        $newContentDictionary = new MstFileContentDictionary;
                        $newContentDictionary->file_dictionary_id = $file_dictionary_id;
                        $newContentDictionary->field = $input_field[$i];
                        $newContentDictionary->from = $input_from[$i];
                        $newContentDictionary->to = $input_to[$i];
                        $newContentDictionary->length = $input_length[$i];
                        $newContentDictionary->description = $input_description[$i];
                        $newContentDictionary->save();
                    }

                    DB::commit();
        
                    Alert('Sukses', 'Berhasil menyimpan data');
                    return redirect()->route('dictionary.index');
                }
            }
            DB::commit();

            Alert('Gagal', 'Item kosong');
            return back();
        } catch (\Exception $e) {
            DB::commit();
            return $e->getMessage();
            Alert::error('Error', $e->getMessage());
            // return back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::commit();
            return $e->getMessage();
            Alert::error('Error', $e->getMessage());
            // return back();
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
        $param['title'] = 'Detail Dictionary';
        $param['pageTitle'] = 'Dictionary';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $param['title'] = 'Edit Dictionary';
        $param['pageTitle'] = 'Dictionary';
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
        try {
            DB::beginTransaction();
            $fileDictionary = MstFileDictionary::find($id);
            $fileDictionary->filename = $request->filename;
            $fileDictionary->description = $request->description;
            $file_dictionary_id = $fileDictionary->save();

            $item_id = $request->get('item_id');
            /**
             * NOTE : item id 0 = Data baru, selain 0 data lama
             */
            $input_field = $request->get('input_field');
            $input_from = $request->get('input_from');
            $input_to = $request->get('input_to');
            $input_length = $request->get('input_length');
            $input_description = $request->get('input_description');

            if ($input_field) {
                if (is_array($input_field)) {
                    for ($i=0; $i < count($item_id); $i++) { 
                        if ($item_id != 0) {
                            // Data lama
                            $contentDictionary = MstFileContentDictionary::find($item_id[$i]);
                            $contentDictionary->file_dictionary_id = $file_dictionary_id;
                            $contentDictionary->field = $input_field[$i];
                            $contentDictionary->from = $input_from[$i];
                            $contentDictionary->to = $input_to[$i];
                            $contentDictionary->length = $input_length[$i];
                            $contentDictionary->description = $input_description[$i];
                            $contentDictionary->save();
                        }
                        else {
                            // Data baru
                            $newContentDictionary = new MstFileContentDictionary;
                            $newContentDictionary->file_dictionary_id = $file_dictionary_id;
                            $newContentDictionary->field = $input_field[$i];
                            $newContentDictionary->from = $input_from[$i];
                            $newContentDictionary->to = $input_to[$i];
                            $newContentDictionary->length = $input_length[$i];
                            $newContentDictionary->description = $input_description[$i];
                            $newContentDictionary->save();
                        }
                    }

                    DB::commit();
        
                    Alert('Sukses', 'Berhasil menyimpan data');
                    return route('dictionary.index');
                }
            }
            DB::commit();

            Alert('Gagal', 'Item kosong');
            return back();
        } catch (\Exception $e) {
            DB::commit();
            Alert::error('Error', $e->getMessage());
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::commit();
            Alert::error('Error', $e->getMessage());
            return back();
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
        try {
            DB::beginTransaction();
            $dictionary = MstFileDictionary::findOrFail($id);
            if ($dictionary)
                $dictionary->delete();

            DB::commit();
            Alert::error('Sukses', 'Berhasil menghapus data');
            return back();
        }  catch (\Exception $e) {
            DB::commit();
            Alert::error('Error', $e->getMessage());
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::commit();
            Alert::error('Error', $e->getMessage());
            return back();
        }
    }
}
