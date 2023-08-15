<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\Action;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
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
        $param['title'] = 'Role/Peran';
        $param['pageTitle'] = 'Role/Peran';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $param['page_length'] = $page_length;

        if($request->ajax()){
            $data = $this->list($page_length, $searchQuery, $searchBy);
            return response()->json(['data'=>$data]);
        }else{
            $data = $this->list($page_length, $searchQuery, $searchBy);
            $param['data'] = $data;
            return view('pages.role.index', $param);
        }
    }

    public function list($page_length =5, $searchQuery, $searchBy)
    {
        $data = Role::orderBy('name');

        if ($searchQuery && $searchBy === 'field') {
            $data->where(function ($q) use ($searchQuery) {
                $q->where('name', '=', $searchQuery);
            });
        }

        if (is_numeric($page_length))
            $data = $data->paginate($page_length);
        else
            $data = $data->get();
        return $data;
    }

    public function search($req, $page_length = 5)
    {
        $data = Role::orderBy('name')
            ->where('name', 'LIKE', '%' . $req . '%');

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
            'name' => 'required|unique:roles,name'
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'name' => 'Nama',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            $newRole = new Role();
            $newRole->name = $request->name;
            $newRole->save();

            $this->logActivity->store('Membuat role '.$request->name.'.');

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

        $currentRole = Role::find($id);
        $isUnique = $request->name && $request->name != $currentRole->name ? '|unique:roles,name' : '';

        $validator = Validator::make($request->all(), [
            'name' => 'required'.$isUnique,
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'name' => 'Nama',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            $currentRole->name = $request->name;
            $currentRole->save();

            $this->logActivity->store("Memperbarui role '".$currentRole->name."' menjadi $request->name.");

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
            $currentRole = Role::findOrFail($id);
            $currentName = $currentRole->name;
            if ($currentRole) {
                $currentRole->delete();
                $this->logActivity->store("Menghapus role $currentName.");

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

    // Permission
    public function indexPermission($id)
    {
        try {
            $param['title'] = 'Hak Akses';
            $param['role'] = Role::select('name')->where('id', $id)->first()->name;
            $param['pageTitle'] = 'Hak Akses '. $param['role'];
            $data = Action::select(
                                'actions.id',
                                'actions.name',
                                \DB::raw("IF ((SELECT COUNT(action_id) FROM permissions WHERE action_id = actions.id AND role_id = $id) = 1, 'checked', 'uncheck'
                                ) AS status")
                            )
                            ->orderBy('actions.id')
                            ->get();
                            // return count($data);
                            // return $data;

            $param['data'] = $data;

            return view('pages.hak_akses.index', $param);
        } catch (\Exception $e) {
            return $e->getMessage();
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return $e->getMessage();
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

    public function storePermission(Request $request)
    {
        try {
            DB::beginTransaction();

            $actions = Action::select('id', 'name')->orderBy('id')->get();
            foreach ($actions as $key => $value) {
                $permission = Permission::select('id')->where('role_id', $request->role_id)->where('action_id', $value->id)->first();
                if (array_key_exists($value->id, $request->check)) {
                    if (!$permission) {
                        // Set a new permission
                        $setPermissions = new Permission();
                        $setPermissions->action_id = $value->id;
                        $setPermissions->role_id = $request->role_id;
                        $setPermissions->save();
                    }
                }
                else {
                    if ($permission)
                        $permission->delete();
                }
            }
            $username = Auth::user()->role_id == 3 ? Auth()->user()->email : Auth()->user()->nip;
            $this->logActivity->store("Pengguna '$username' menyimpan pengaturan hak akses.");
            DB::commit();

            return back()->withStatus('Berhasil menyimpan hak akses');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return back()->withError('Terjadi kesalahan pada database');
        }
    }
}
