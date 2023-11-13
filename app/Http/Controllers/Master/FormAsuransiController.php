<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\MstFormAsuransi;
use App\Models\MstFormItemAsuransi;
use App\Models\MstPerusahaanAsuransi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormAsuransiController extends Controller
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
        $data = MstFormAsuransi::select('mst_form_asuransi.*', 'p.nama')
                ->join('mst_perusahaan_asuransi AS p','mst_form_asuransi.perusahaan_id','=','p.id')
                ->join('mst_form_item_asuransi AS i','mst_form_asuransi.form_item_asuransi_id','=','i.id')
                ->orderBy('id');
        if ($searchQuery && $searchBy === 'field') {
            $data->where(function ($q) use ($searchQuery) {
                $q->where('p.nama', 'like', '%' . $searchQuery . '%')
                ->orWhere('i.label', 'like', '%' . $searchQuery . '%')
                ->orWhere('i.level', 'like', '%' . $searchQuery . '%')
                ->orWhere('i.type', 'like', '%' . $searchQuery . '%')
                ->orWhere('i.formula', 'like', '%' . $searchQuery . '%')
                ->orWhere('i.sequence', 'like', '%' . $searchQuery . '%')
                ->orWhere('i.only_accept', 'like', '%' . $searchQuery . '%');
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

            $user_name = \Session::get(config('global.user_name_session'));
            $token = \Session::get(config('global.user_token_session'));
            $user = $token ? $this->getLoginSession() : Auth::user();
            $name = $token ? $user['data']['nip'] : $user->email;

            $this->logActivity->store('Pengguna ' . $user_name . '(' . $name . ')' . ' Menambahkan data Form Asuransi.','',1);

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
        $status = '';
        $message = '';

        try {
            DB::beginTransaction();

            $currentFormAsuransi = MstFormAsuransi::with(['perusahaanAsuransi','itemAsuransi'])->findOrFail($id);
            $currentJenisNama = $currentFormAsuransi->perusahaanAsuransi->nama;
            $currentJenisLabel = $currentFormAsuransi->itemAsuransi->label;
            if ($currentFormAsuransi) {
                $currentFormAsuransi->delete();

                $user_name = \Session::get(config('global.user_name_session'));
                $token = \Session::get(config('global.user_token_session'));
                $user = $token ? $this->getLoginSession() : Auth::user();
                $name = $token ? $user['data']['nip'] : $user->email;

                $this->logActivity->store('Pengguna ' . $user_name . '(' . $name . ')' . " Menghapus data Form Asuransi '$currentJenisNama' Label '$currentJenisLabel'.", '', 1);

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
