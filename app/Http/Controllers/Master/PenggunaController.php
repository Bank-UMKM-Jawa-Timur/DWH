<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
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

    public function listCabang()
    {
        return User::select(
                    'users.id',
                    'users.nip',
                    'r.name AS role_name',
                )
                ->join('roles AS r', 'r.id', 'users.role_id')
                ->where('r.name', 'Cabang')
                ->orderBy('nip')
                ->get();
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
            
            $title = $request->nip ? $request->nip : $request->email;
            $this->logActivity->store("Membuat data pengguna $title.");
            
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
            $this->logActivity->store("Memperbarui data pengguna.");

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
            $currentName = $currentRole->name;
            if ($currentRole) {
                $currentRole->delete();
                $this->logActivity->store("Menghapus data pengguna $currentName.");

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

    public function resetPassword(Request $request)
    {
        $status = '';
        $message = '';

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
        ], [
            'required' => ':attribute harus diisi.',
            'min' => 'Minimal harus menggunakan 8 karakter.'
        ], [
            'password' => 'Password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            $user = User::find($request->id);
            $user->password = \Hash::make($request->password);
            $user->save();

            $username = $user->nip ? $user->nip : $user->email;
            $actor = Auth::user()->nip ? Auth::user()->nip : Auth::user()->email;

            $this->logActivity->store("Password dari pengguna '$username' telah direset oleh '$actor'.");

            $status = 'success';
            $message = 'Berhasil mereset password';
        } catch (\Exception $e) {
            $status = 'error';
            $message = 'Terjadi kesalahan. '.$e->getMessage();

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
