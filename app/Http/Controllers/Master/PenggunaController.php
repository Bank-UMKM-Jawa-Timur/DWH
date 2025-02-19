<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    private $logActivity;
    private $losHeaders;
    private $losHost;

    function __construct()
    {
        $this->logActivity = new LogActivitesController;
        $this->losHost = config('global.los_api_host');
        $this->losHeaders = [
            'token' => config('global.los_api_token')
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param['title'] = 'Pengguna';
        $param['pageTitle'] = 'Pengguna';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy );
        $param['data'] = $data;

        return view('pages.pengguna.index', $param);
    }

    public function list($page_length = 5, $searchQuery, $searchBy)
    {
        $user = User::select(
            'users.*',
            'r.name AS role',
        )
        ->join('roles AS r', 'r.id', 'users.role_id')
        ->orderBy('users.id');
        if ($searchQuery && $searchBy === 'field') {
            $user->where(function ($q) use ($searchQuery) {
                $q->where('nip', '=', $searchQuery)
                    ->orWhere('email', '=', $searchQuery);
            });
        }
        if (is_numeric($page_length)) {
            $data = $user->paginate($page_length);
        } else {
            $data = $user->get();
        }

        return $data;
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
        $token = \Session::get(config('global.user_token_session'));
        $this->losHeaders['Authorization'] = "Bearer $token";

        try {
            // retrieve kode_cabang from api
            $kode_cabang = '';
            $apiURL = $this->losHost.'/kkb/get-data-users/'.$request->nip;

            try {
                $response = Http::withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);

                $kode_cabang = $responseBody['kode_cabang'];
            } catch(\Illuminate\Http\Client\ConnectionException $e) {
                // return $e->getMessage();
            }
            $newUser = new User();
            $newUser->nip = $request->nip;
            $newUser->email = $request->email;
            $newUser->kode_cabang = $kode_cabang;
            $newUser->password = \Hash::make($request->password);
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
        $message = $request->all();
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

    public function getKaryawan($nip)
    {
        // retrieve from api
        $host = env('BIO_INTERFACE_API_HOST');
        $apiURL = $host . '/karyawan';

        try {
            $response = Http::timeout(3)->get($apiURL, [
                'nip' => $nip
            ]);

            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);
            if ($responseBody) {
                if (array_key_exists('data', $responseBody))
                    return $responseBody['data'];
                else
                    return $responseBody;
                return $responseBody;
            }
            return $responseBody;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $e->getMessage();
        }
    }
}
