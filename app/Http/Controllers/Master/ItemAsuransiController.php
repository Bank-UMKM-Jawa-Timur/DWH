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
        $data = MstFormItemAsuransi::select('mst_form_item_asuransi.*', 'p.label AS parent')
                                    ->leftJoin('mst_form_item_asuransi AS p', 'p.id', 'mst_form_item_asuransi.parent_id')
                                    ->orderBy('mst_form_item_asuransi.sequence', 'ASC');
        if ($searchQuery && $searchBy === 'field') {
            $data->where(function ($q) use ($searchQuery) {
                $q->where('mst_form_item_asuransi.label', 'like', '%' . $searchQuery . '%')
                ->orWhere('mst_form_item_asuransi.level', 'like', '%' . $searchQuery . '%')
                ->orWhere('p.label', 'like', '%' . $searchQuery . '%')
                ->orWhere('mst_form_item_asuransi.type', 'like', '%' . $searchQuery . '%')
                ->orWhere('mst_form_item_asuransi.sequence', 'like', '%' . $searchQuery . '%')
                ->orWhere('mst_form_item_asuransi.only_accept', 'like', '%' . $searchQuery . '%');
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
        $last_sequence = 1;
        $dataField = MstFormItemAsuransi::orderBy('sequence')->get();
        if (count($dataField) > 0) {
            $last_index = count($dataField) - 1;
            $last_sequence = $dataField[$last_index]->sequence;
        }

        return view('pages.mst-item-asuransi.create', compact('dataField', 'last_sequence'));
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

        $validator = Validator::make($request->all(), [
            'label' => 'required',
            'level' => 'required',
            'sequence' => 'required|unique:mst_form_item_asuransi,sequence',
            'only_accept' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'label' => 'Label',
            'level' => 'Level',
            'sequence' => 'Urutan',
            'only_accept' => 'Only Accept',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

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
            $newItem->function = $request->function;
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
            $url = route('mst-item-asuransi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan. '.$e->getMessage();
            $url = back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database. '.$e->getMessage();
            $url = back();
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
            $dataField = MstFormItemAsuransi::orderBy('id')->get();
            if($data) {
                $itemValue = DB::table('mst_option_values')
                                ->select('id', 'form_asuransi_id', 'value', 'display_value')
                                ->where('form_asuransi_id', $data->id)
                                ->get();
                return view('pages.mst-item-asuransi.edit', compact(['data', 'dataField', 'itemValue']));
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
        $url = route('mst-item-asuransi.index');

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
            $temp_type = $request->type;

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
            $updated->function = $request->function;
            $updated->updated_at = now();
            $updated->save();

            $item_id = $request->item_id;
            $item_val = $request->item_val;
            $item_display_val = $request->item_display_val;


            if (($temp_type == 'option' || $temp_type == 'radio') && ($request->type != 'option' && $request->type != 'radio')) {
                DB::table('mst_option_values')
                                ->where('form_asuransi_id', $id)
                                ->delete();
            }

            if ($request->type == 'option' || $request->type == 'radio') {
                if (is_array($item_id) && is_array($item_val) && is_array($item_display_val)) {
                    if (count($item_id) == count($item_val) &&
                        count($item_id) && count($item_display_val) &&
                        count($item_val) == count($item_display_val)) {
                        /**
                         * 1. Get old items
                         * 2. If old item doesn't in new item then delete the item
                         */
                        $oldItemId = DB::table('mst_option_values')
                                        ->where('form_asuransi_id', $id)
                                        ->pluck('id');

                        for ($i=0; $i < count($oldItemId); $i++) {
                            if (!in_array($oldItemId[$i], $item_id)) {
                                // Delete old item
                                DB::table('mst_option_values')->where('id', $oldItemId[$i])->delete();
                            }
                        }

                        // Loop for new item or old item
                        for ($i = 0; $i < count($item_id); $i++) {
                            if ($item_id[$i] != 0) {
                                // Data lama
                                DB::table('mst_option_values')
                                    ->where('id', $item_id[$i])
                                    ->update([
                                        'form_asuransi_id' => $id,
                                        'value' => $item_val[$i],
                                        'display_value' => $item_display_val[$i],
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ]);
                            }
                            else {
                                // Data baru
                                DB::table('mst_option_values')->insert([
                                    'form_asuransi_id' => $id,
                                    'value' => $item_val[$i],
                                    'display_value' => $item_display_val[$i],
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]);
                            }
                        }
                    }
                    else {
                        DB::rollBack();
                        $status = 'failed';
                        $message = 'Harap lengkapi kolom pada tabel item.';
                        throw new Exception("Kolom item tidak lengkap");
                    }
                }
            }

            $user_name = \Session::get(config('global.user_name_session'));
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $name = $token ? $user['data']['nip'] : $user->email;

            $this->logActivity->store('Pengguna ' . $user_name . '(' . $name . ')' . ' memperbarui data Item Form Asuransi.','',1);

            DB::commit();

            $status = 'success';
            $message = 'Berhasil mengubah data';
        } catch (Exception $e){
            DB::rollBack();
            $status = 'failed';
            $message = $e->getMessage();
        } catch (QueryException $e){
            DB::rollBack();
            $status = 'failed';
            $message = $e->getMessage();
        }
        finally{
            return response()->json([
                'status' => $status,
                'message' => $message,
                'url' => $url
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
                if ($currentItemAsuransi->type == 'option' || $currentItemAsuransi->type == 'radio') {
                    DB::table('mst_option_values')
                        ->where('form_asuransi_id', $id)
                        ->delete();
                }
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
