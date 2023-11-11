<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PerusahaanAsuransi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PerusahaanAsuransiController extends Controller
{

    private $logActivity;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param['title'] = 'Perusahaan Asuransi';
        $param['pageTitle'] = 'Perusahaan Asuransi';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;

        return view('pages.perusahaan_asuransi.index', $param);
    }

    public function list($page_length = 5 , $searchQuery, $searchBy)
    {
        $query = PerusahaanAsuransi::
                        orderBy('nama');
        if ($searchQuery && $searchBy === 'field') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('nama', 'LIKE', "%$searchQuery%")
                    ->orWhere('alamat', 'LIKE', "%$searchQuery%")
                    ->orWhere('telp', 'LIKE', "%$searchQuery%");
            });
        }
        if (is_numeric($page_length)) {
            $data = $query->paginate($page_length);
        } else {
            $data = $query->get();
        }

        return $data;
    }

    public function search($req, $page_length = 5)
    {
        $data = PerusahaanAsuransi::orderBy('nama')
            ->where('nama', 'LIKE', '%' . $req . '%');

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
            'nama' => 'required|unique:mst_perusahaan_asuransi,nama',
            'alamat' => 'required',
            'telp' => 'required|unique:mst_perusahaan_asuransi,telp',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'nama' => 'Nama',
            'telp' => 'Nomor HP',
            'alamat' => 'Alamat',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            DB::beginTransaction();

            $newPerAsuransi = new PerusahaanAsuransi();
            $newPerAsuransi->nama = $request->nama;
            $newPerAsuransi->alamat = $request->alamat;
            $newPerAsuransi->telp = $request->telp;
            $newPerAsuransi->save();

            $this->logActivity->store("Membuat data Perusahaan Asuransi $request->nama.");

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

        $currentPerAsuransi = PerusahaanAsuransi::find($id);
        $isUniqueName = $request->nama && $request->nama != $currentPerAsuransi->nama ? '|unique:mst_perusahaan_asuransi,nama' : '';
        $isUniquePhone = $request->telp && $request->telp != $currentPerAsuransi->telp ? '|unique:mst_perusahaan_asuransi,telp' : '';

        $validator = Validator::make($request->all(), [
            'nama' => 'required'.$isUniqueName,
            'alamat' => 'required',
            'telp' => 'required'.$isUniquePhone,
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
            'not_in' => ':attribute harus dipilih.'
        ], [
            'nama' => 'Nama',
            'telp' => 'Nomor HP',
            'alamat' => 'Alamat',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            DB::beginTransaction();

            $currentPerAsuransi->nama = $request->nama;
            $currentPerAsuransi->telp = $request->telp;
            $currentPerAsuransi->alamat = $request->alamat;
            $currentPerAsuransi->save();

            $this->logActivity->store("Memperbarui data Perusahaan Asuransi.");

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

            $currentPerAsuransi = PerusahaanAsuransi::findOrFail($id);
            $currentNama = $currentPerAsuransi->nama;
            if ($currentPerAsuransi) {
                $currentPerAsuransi->delete();
                $this->logActivity->store("Menghapus data Perusahaan Asuransi '$currentNama'.");

                $status = 'success';
                $message = 'Berhasil menghapus data.';
            }
            else {
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

    public function form()
    {
        return view('pages.perusahaan_asuransi.form-asuransi');
    }
}
