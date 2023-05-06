<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $param['title'] = 'Vendor';
        $param['pageTitle'] = 'Vendor';
        $param['data'] = $this->list();

        return view('pages.vendor.index', $param);
    }

    public function list()
    {
        return Vendor::orderBy('name')->get();
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
            'name' => 'required|unique:vendors,name',
            'phone' => 'required|unique:vendors,phone',
            'cabang_id' => 'not_in:0',
            'address' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
            'not_in' => ':attribute harus dipilih.',
        ], [
            'name' => 'Nama',
            'phone' => 'Nomor HP',
            'cabang_id' => 'NIP Cabang',
            'address' => 'Alamat',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            $newVendor = new Vendor();
            $newVendor->name = $request->name;
            $newVendor->phone = $request->phone;
            $newVendor->cabang_id = $request->cabang_id;
            $newVendor->address = $request->address;
            $newVendor->save();

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

        $currentVendor = Vendor::find($id);
        $isUniqueName = $request->name && $request->name != $currentVendor->name ? '|unique:vendors,name' : '';
        $isUniquePhone = $request->phone && $request->phone != $currentVendor->phone ? '|unique:vendors,phone' : '';

        $validator = Validator::make($request->all(), [
            'name' => 'required'.$isUniqueName,
            'phone' => 'required'.$isUniquePhone,
            'address' => 'required',
            'cabang_id' => 'not_in:0'
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
            'not_in' => ':attribute harus dipilih.'
        ], [
            'name' => 'Nama',
            'phone' => 'Nomor HP',
            'address' => 'Alamat',
            'cabang_id' => 'NIP Cabang'
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            $currentVendor->name = $request->name;
            $currentVendor->phone = $request->phone;
            $currentVendor->address = $request->address;
            $currentVendor->cabang_id = $request->cabang_id;
            $currentVendor->save();

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
            $currentRole = Vendor::findOrFail($id);
            if ($currentRole) {
                $currentRole->delete();
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
}
