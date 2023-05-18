<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TargetController extends Controller
{
    private $param;
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
    public function index()
    {
        $this->param['title'] = 'Target Cabang';
        $this->param['pageTitle'] = 'Target';
        $this->param['data'] = Target::select('id', 'nominal', 'is_active')->orderBy('is_active', 'desc')->paginate(5);

        return view('pages.target.index', $this->param);
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
            'nominal' => 'required|unique:target,nominal'
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'nominal' => 'Nominal',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            $newRole = new Target();
            $newRole->nominal = $request->nominal;
            $newRole->save();

            $this->logActivity->store('Membuat target sebesar Rp. '.number_format($request->nominal, 0, ',', '.').'.');

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
        $status = '';
        $message = '';

        $currentData = Target::find($id);
        $isUnique = $request->nominal && $request->nominal != $currentData->nominal ? '|unique:target,nominal' : '';

        $validator = Validator::make($request->all(), [
            'nominal' => 'required'.$isUnique,
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'nominal' => 'Nominal',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            $currentData->nominal = $request->nominal;
            $currentData->save();

            $this->logActivity->store("Memperbarui target dari ".number_format($currentData->nominal, 0, ',', '.')." menjadi ".number_format($request->nominal, 0, ',', '.').".");

            $status = 'success';
            $message = 'Berhasil menyimpan perubahan';
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
            $currentData = Target::findOrFail($id);
            $currentNominal = $currentData->nominal;
            if ($currentData) {
                $currentData->delete();
                $this->logActivity->store("Menghapus target yang bernilai ".number_format($currentNominal, 0, ',', '.').".");

                $status = 'success';
                $message = 'Berhasil menghapus data.';
            }
            else {
                $status = 'error';
                $message = 'Data tidak ditemukan.';
            }
        } catch (\Exception $e) {
            $status = 'error';
            $message = 'Terjadi kesalahan.';

        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'error';
            $message = 'Terjadi kesalahan pada database.';
        } finally {
            return $status == 'success' ? back()->withStatus($message) : back()->withError($message);
        }
    }

    public function toggle($id, Request $request)
    {
        $status = '';
        $message = '';

        try {
            $isOn = $request->toggle == 'true';
            if ($isOn) {
                $currentData = Target::findOrFail($id);
                if ($currentData) {
                    $currentData->is_active = 1;
                    $currentData->save();
                    Target::whereNot('id', $id)->update(['is_active' => 0]);

                    $this->logActivity->store("Mengaktifkan target yang bernilai ".number_format($currentData->nominal, 0, ',', '.').".");

                    $status = 'success';
                    $message = 'Berhasil mengaktifkan target.';
                }
                else {
                    $status = 'error';
                    $message = 'Data tidak ditemukan.';
                }
            }
            else {
                $currentData = Target::findOrFail($id);
                if ($currentData) {
                    $currentData->is_active = 0;
                    $currentData->save();
    
                    $this->logActivity->store("Menonaktifkan target yang bernilai ".number_format($currentData->nominal, 0, ',', '.').".");
    
                    $status = 'success';
                    $message = 'Berhasil menonaktifkan target.';
                }
                else {
                    $status = 'error';
                    $message = 'Data tidak ditemukan.';
                }
            }
        } catch (\Exception $e) {
            $status = 'error';
            $message = 'Terjadi kesalahan.';

        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'error';
            $message = 'Terjadi kesalahan pada database.';
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }
}
