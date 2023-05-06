<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $param['title'] = 'Pengguna';
        $param['pageTitle'] = 'Pengguna';
        $data = User::select(
                'users.*',
                'r.name AS role',
            )
            ->join('roles AS r', 'r.id', 'users.role_id')
            ->orderBy('users.id')
            ->get();
        $param['data'] = $data;

        return view('pages.pengguna.index', $param);
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
            'nip' => $request->nip ? 'unique:users,nip' : '',
            'email' => $request->email ? 'unique:users,email' : '',
            'password' => 'required|min:8',
            'role_id' => 'not_in:0',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
            'not_in' => ':attribute harus dipilih.',
            'min' => 'Minimal adalah 8 karakter.'
        ], [
            'nip' => 'NIP',
            'email' => 'Email',
            'password' => 'Password',
            'role_id' => 'Role'
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            $newUser = new User();
            $newUser->nip = $request->nip;
            $newUser->email = $request->email;
            $newUser->password = $request->password;
            $newUser->role_id = $request->role_id;
            $newUser->save();

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

        $currentUser = User::find($id);
        $isUniqueNip = $request->nip && $request->nip != $currentUser->nip ? '|unique:users,nip' : '';
        $isUniqueEmail = $request->email && $request->email != $currentUser->email ? 'unique:users,email' : '';

        $validator = Validator::make($request->all(), [
            'nip' => $request->nip ? 'numeric'.$isUniqueNip : '',
            'email' => $request->email ? $isUniqueEmail : '',
            'role_id' => 'not_in:0'
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
            'not_in' => ':attribute harus dipilih.'
        ], [
            'nip' => 'NIP',
            'email' => 'Email',
            'role_id' => 'Role'
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            $currentUser->nip = $request->nip;
            $currentUser->email = $request->email;
            $currentUser->role_id = $request->role_id;
            if ($request->password)
                $currentUser->password = \Hash::make($request->password);
            $currentUser->save();

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
            $currentRole = User::findOrFail($id);
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
