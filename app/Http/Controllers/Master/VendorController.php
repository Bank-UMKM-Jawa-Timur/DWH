<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
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
        $param['title'] = 'Vendor';
        $param['pageTitle'] = 'Vendor';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;

        return view('pages.vendor.index', $param);
    }

    public function list($page_length = 5 , $searchQuery, $searchBy)
    {
        $query = Vendor::orderBy('name');
        if ($searchQuery && $searchBy === 'field') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', '=', $searchQuery)
                    ->orWhere('address', '=', $searchQuery)
                    ->orWhere('phone', '=', $searchQuery);
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
        $data = Vendor::orderBy('name')
        ->where('name', 'LIKE', '%' . $req . '%')
            ->orWhere('address', 'LIKE', '%' . $req . '%')
            ->orWhere('phone', 'LIKE', '%' . $req . '%');

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
            'name' => 'required|unique:vendors,name',
            'phone' => 'required|unique:vendors,phone',
            'email' => 'required|unique:users,email',
            'cabang_id' => 'not_in:0',
            'address' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
            'not_in' => ':attribute harus dipilih.',
        ], [
            'name' => 'Nama',
            'phone' => 'Nomor HP',
            'email' => 'Email',
            'cabang_id' => 'NIP Cabang',
            'address' => 'Alamat',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            \DB::beginTransaction();

            $newVendor = new Vendor();
            $newVendor->name = $request->name;
            $newVendor->phone = $request->phone;
            // $newVendor->cabang_id = $request->cabang_id;
            $newVendor->address = $request->address;
            $newVendor->save();

            $newUser = new User();
            $newUser->email = $request->email;
            $newUser->vendor_id = $newVendor->id;
            $newUser->role_id = 3;
            $newUser->password = \Hash::make('12345678');
            $newUser->save();

            $this->logActivity->store("Membuat data vendor $request->name.");

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            \DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan';
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } finally {
            \DB::commit();
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
        $isUniqueEmail = $request->email && $request->email != $currentVendor->email ? '|unique:users,email' : '';

        $validator = Validator::make($request->all(), [
            'name' => 'required'.$isUniqueName,
            'phone' => 'required'.$isUniquePhone,
            'email' => 'required'.$isUniqueEmail,
            'address' => 'required',
            'cabang_id' => 'not_in:0'
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
            'not_in' => ':attribute harus dipilih.'
        ], [
            'name' => 'Nama',
            'phone' => 'Nomor HP',
            'email' => 'Email',
            'address' => 'Alamat',
            'cabang_id' => 'NIP Cabang'
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            \DB::beginTransaction();

            $currentVendor->name = $request->name;
            $currentVendor->phone = $request->phone;
            $currentVendor->address = $request->address;
            $currentVendor->cabang_id = $request->cabang_id;
            $currentVendor->save();

            $user = User::where('vendor_id', $id)->first();
            $user->email = $request->email;
            $user->save();

            $this->logActivity->store("Memperbarui data vendor.");

            $status = 'success';
            $message = 'Berhasil menyimpan perubahan';
        } catch (\Exception $e) {
            \DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan';
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } finally {
            \DB::commit();
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
            \DB::beginTransaction();

            $currentVendor = Vendor::findOrFail($id);
            $currentName = $currentVendor->name;
            if ($currentVendor) {
                $currentVendor->delete();
                User::where('vendor_id', $id)->delete();
                $this->logActivity->store("Menghapus data vendor '$currentName'.");

                $status = 'success';
                $message = 'Berhasil menghapus data.';
            }
            else {
                $status = 'error';
                $message = 'Data tidak ditemukan.';
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            $status = 'error';
            $message = 'Terjadi kesalahan.';

        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            $status = 'error';
            $message = 'Terjadi kesalahan pada database.';
        } finally {
            \DB::commit();
            return $status == 'success' ? back()->withStatus($message) : back()->withError($message);
        }
    }
}
