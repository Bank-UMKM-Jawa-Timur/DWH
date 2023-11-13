<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\MstFormItemAsuransi;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ItemAsuransiController extends Controller
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
        $param['title'] = 'Master List Item';
        $param['pageTitle'] = 'Master List Item';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;

        return view('pages.mst-item-asuransi.index', $param);
    }

    public function list($page_length =5, $searchQuery, $searchBy)
    {
        $data = MstFormItemAsuransi::orderBy('sequence', 'ASC');
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dataField = MstFormItemAsuransi::orderBy('id', 'ASC')->get();

        return view('pages.mst-item-asuransi.create', compact(['dataField']));
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
        $url = null;

        // $validator = Validator::make($request->all(), [
        //     'label' => 'required',
        //     'level' => 'required',
        //     'sequence' => 'required',
        //     'only_accept' => 'required',
        // ], [
        //     'required' => ':attribute harus diisi.',
        //     'unique' => ':attribute telah digunakan.',
        // ], [
        //     'label' => 'Label',
        //     'level' => 'Level',
        //     'sequence' => 'Sequence',
        //     'only_accept' => 'Only Accept',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => $validator->errors()->all()
        //     ]);
        // }

        DB::beginTransaction();
        try {
            $newItem =  new MstFormItemAsuransi();
            $newItem->label = $request->label;
            $newItem->level = $request->level;
            $newItem->parent_id = $request->parent_id;
            $newItem->type = $request->type;
            $newItem->formula = $request->formula;
            $newItem->sequence = $request->sequence;
            $newItem->only_accept = $request->only_accept;
            $newItem->rupiah = $request->rupiah;
            $newItem->readonly = $request->readonly;
            $newItem->hidden = $request->hidden;
            $newItem->disabled = $request->disabled;
            $newItem->required = $request->required;
            $newItem->save();

            $item_val = $request->item_val;
            $item_display_val = $request->item_display_val;

            if ($request->type == 'option' || $request->type == 'radio') {
                if (count($item_val) == count($item_display_val)) {
                    for ($i=0; $i < count($item_val); $i++) { 
                        DB::table('mst_option_values')->insert([
                            'form_asuransi_id' => $newItem->id,
                            'value' => $item_val[$i],
                            'display_value' => $item_display_val[$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
                else {
                    DB::rollBack();
                    $status = 'failed';
                    $message = 'Harap lengkapi kolom pada tabel item.';
                    throw new Exception("Kolom item tidak lengkap");
                }
            }

            $user_name = \Session::get(config('global.user_name_session'));
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $name = $token ? $user['data']['nip'] : $user->email;

            $this->logActivity->store('Pengguna ' . $user_name . '(' . $name . ')' . ' Menambahkan data Item Form Asuransi.','',1);

            DB::commit();
            $status = 'success';
            $message = 'Berhasil menyimpan data';
            $url = redirect()->route('mst-item-asuransi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan. '.$e->getMessage();
            $url = redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database. '.$e->getMessage();
            $url = redirect()->back();
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
                'url' => $url,
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
        try{
            $data = MstFormItemAsuransi::find($id);
            $dataField = MstFormItemAsuransi::orderBy('id', 'ASC')->get();
            if($data) {
                // dd($data);
                return view('pages.mst-item-asuransi.detail', compact(['data', 'dataField']));
            } else{
                Alert::error('Gagal', 'Data tidak ditemukan');
                return back();
            }
        } catch(Exception $e){
            Alert::error('Gagal', $e->getMessage());
            return back();
        } catch(QueryException $e){
            Alert::error('Gagal', $e->getMessage());
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
        try{
            $data = MstFormItemAsuransi::find($id);
            $dataField = MstFormItemAsuransi::orderBy('id', 'ASC')->get();
            if($data) {
                // dd($data);
                return view('pages.mst-item-asuransi.edit', compact(['data', 'dataField']));
            } else{
                Alert::error('Gagal', 'Data tidak ditemukan');
                return back();
            }
        } catch(Exception $e){
            Alert::error('Gagal', $e->getMessage());
            return back();
        } catch(QueryException $e){
            Alert::error('Gagal', $e->getMessage());
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

        DB::beginTransaction();
        try{
            $updated = MstFormItemAsuransi::find($id);
            $updated->label = $request->label;
            $updated->level = $request->level;
            $updated->parent_id = $request->parent_id;
            $updated->type = $request->type;
            $updated->formula = $request->formula;
            $updated->sequence = $request->sequence;
            $updated->only_accept = $request->only_accept;
            $updated->rupiah = $request->rupiah;
            $updated->readonly = $request->readonly;
            $updated->hidden = $request->hidden;
            $updated->disabled = $request->disabled;
            $updated->required = $request->required;
            $updated->updated_at = now();
            $updated->save();

            DB::commit();

            $status = 'success';
            $message = 'Berhasil mengubah data';
        } catch (Exception $e){
            $status = 'failed';
            $message = $e->getMessage();
        } catch (QueryException $e){
            $status = 'failed';
            $message = $e->getMessage();
        } finally{
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
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

            $currentItemAsuransi = MstFormItemAsuransi::findOrFail($id);
            if ($currentItemAsuransi) {
                $currentItemAsuransi->delete();

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
