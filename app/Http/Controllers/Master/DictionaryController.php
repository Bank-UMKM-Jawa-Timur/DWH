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
        
                    Alert::success('Sukses', 'Berhasil menyimpan data');
                    return redirect()->route('dictionary.index');
                }
            }
            DB::commit();

            Alert::error('Gagal', 'Item kosong');
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $param['title'] = 'Detail Dictionary';
        $param['pageTitle'] = 'Dictionary';

        try {
            $fileDictionary = MstFileDictionary::find($id);

            if ($fileDictionary)
                $itemDictionary = MstFileContentDictionary::where('file_dictionary_id', $id)->get();
            
            $param['fileDictionary'] = $fileDictionary;
            $param['itemDictionary'] = $itemDictionary;

            return view('pages.dictionary.show', $param);
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            Alert::error('Error', $e->getMessage());
            return back();
        }
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

        try {
            $fileDictionary = MstFileDictionary::find($id);

            if ($fileDictionary)
                $itemDictionary = MstFileContentDictionary::where('file_dictionary_id', $id)->get();
            
            $param['fileDictionary'] = $fileDictionary;
            $param['itemDictionary'] = $itemDictionary;

            return view('pages.dictionary.edit', $param);
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            Alert::error('Error', $e->getMessage());
            return back();
        }
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
            $fileDictionary->save();

            $item_id = $request->get('item_id');
            /**
             * NOTE : item id 0 = Data baru, selain 0 data lama
             */
            // return $request;
            $input_field = $request->get('input_field');
            $input_from = $request->get('input_from');
            $input_to = $request->get('input_to');
            $input_length = $request->get('input_length');
            $input_description = $request->get('input_description');

            if ($input_field) {
                if (is_array($input_field)) {
                    /**
                     * 1. Get old items
                     * 2. If old item doesn't in new item then delete the item
                     */
                    $oldItemId = MstFileContentDictionary::where('file_dictionary_id', $id)->pluck('id');
                    for ($i=0; $i < count($oldItemId); $i++) { 
                        if (!in_array($oldItemId[$i], $item_id)) {
                            // Delete old item
                            // return 'old item : '.$oldItemId[$i];
                            $oldItem = MstFileContentDictionary::find($oldItemId[$i]);
                            if ($oldItem)
                                $oldItem->delete();
                        }
                    }

                    // Loop for new item or old item
                    for ($i = 0; $i < count($item_id); $i++) {
                        if ($item_id[$i] != 0) {
                            // Data lama
                            $contentDictionary = MstFileContentDictionary::find($item_id[$i]);
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
                            $newContentDictionary->file_dictionary_id = $id;
                            $newContentDictionary->field = $input_field[$i];
                            $newContentDictionary->from = $input_from[$i];
                            $newContentDictionary->to = $input_to[$i];
                            $newContentDictionary->length = $input_length[$i];
                            $newContentDictionary->description = $input_description[$i];
                            $newContentDictionary->save();
                        }
                    }

                    DB::commit();
        
                    Alert::success('Sukses', 'Berhasil menyimpan data');
                    return redirect()->route('dictionary.index');
                }
            }
            DB::commit();

            Alert::error('Gagal', 'Item kosong');
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
            Alert::success('Sukses', 'Berhasil menghapus data');
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
