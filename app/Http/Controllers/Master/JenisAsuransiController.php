<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\JenisAsuransi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JenisAsuransiController extends Controller
{
    private $logActivity;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param['title'] = 'Jenis Asuransi';
        $param['pageTitle'] = 'Jenis Asuransi';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;

        return view('pages.jenis_asuransi.index', $param);
    }

    public function list($page_length = 5 , $searchQuery, $searchBy)
    {
        $query = JenisAsuransi::
                        orderBy('produk_kredit_id');
        if ($searchQuery && $searchBy === 'field') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('produk_kredit_id', 'LIKE', "%$searchQuery%")
                    ->orWhere('jenis_kredit', 'LIKE', "%$searchQuery%")
                    ->orWhere('jenis', 'LIKE', "%$searchQuery%");
            });
        }
        if (is_numeric($page_length)) {
            $data = $query->paginate($page_length);
        } else {
            $data = $query->get();
        }

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
            'jenis_kredit' => 'required',
            'jenis' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'jenis_kredit' => 'Jenis Kredit',
            'jenis' => 'Jenis',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            DB::beginTransaction();

            $newJenisAsuransi = new JenisAsuransi();
            $newJenisAsuransi->jenis_kredit = $request->jenis_kredit;
            $newJenisAsuransi->jenis = $request->jenis;
            $newJenisAsuransi->save();

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

        $validator = Validator::make($request->all(), [
            'jenis_kredit' => 'required',
            'jenis' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'jenis_kredit' => 'Jenis Kredit',
            'jenis' => 'Jenis',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            DB::beginTransaction();

            $newJenisAsuransi = JenisAsuransi::find($id);
            $newJenisAsuransi->jenis_kredit = $request->jenis_kredit;
            $newJenisAsuransi->jenis = $request->jenis;
            $newJenisAsuransi->save();

            $this->logActivity->store("Memperbarui data Jenis Asuransi.");

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

            $currentJenisAsuransi = JenisAsuransi::findOrFail($id);
            $currentJenisKredit = $currentJenisAsuransi->jenis_kredit;
            if ($currentJenisAsuransi) {
                $currentJenisAsuransi->delete();
                $this->logActivity->store("Menghapus data Jenis Asuransi '$currentJenisKredit'.");

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
}
