<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $param['title'] = 'Role/Peran';
        $param['pageTitle'] = 'Role/Peran';
        $data = Role::orderBy('name')->get();
        $param['data'] = $data;

        return view('pages.role.index', $param);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                [
                    'name' => 'required|unique:roles,name'
                ],
                [
                    'required' => ':attribute harus diisi.',
                    'unique' => ':attribute telah digunakan.',
                ],
                [
                    'name' => 'Nama'
                ]
            ]);

            $newRole = new Role();
            $newRole->name = $request->name;
            $newRole->save();

            return redirect('/master/role');
        } catch (\Exception $e) {
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database.');
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
        try {
            $currentRole = Role::find($id);
            $isUnique = $request->name && $request->name != $currentRole->name ? '|unique:roles,name' : '';

            $request->validate([
                [
                    'name' => 'required'.$isUnique,
                ],
                [
                    'required' => ':attribute harus diisi.',
                    'unique' => ':attribute telah digunakan.',
                ],
                [
                    'name' => 'Nama'
                ]
            ]);
            $currentRole->name = $request->name;
            $currentRole->save();

            return redirect('/master/role');
        } catch (\Exception $e) {
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database.');
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
