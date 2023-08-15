<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\NotificationTemplate;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificationTemplateController extends Controller
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
        $param['title'] = 'Notifikasi Template';
        $param['pageTitle'] = 'Notifikasi Template';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;
        $param['roles'] = Role::orderBy('name', 'ASC')->get();
        $param['actions'] = DB::table('actions')
            ->where('name', 'like', '%KKB-%')
            ->where('name', '!=', 'KKB-List')
            ->where('name', '!=', 'KKB-detail data')
            ->orderBy('name', 'ASC')
            ->get();
        // return $this->list();
        return view('pages.notifikasi_template.index', $param);
    }

    public function list($page_length =5, $searchQuery, $searchBy)
    {
        $data = NotificationTemplate::orderBy('notification_templates.id');
        if ($searchQuery && $searchBy === 'field') {
            $data->where(function ($q) use ($searchQuery) {
                $q->where('title', '=', $searchQuery)
                    ->orWhere('content', '=', $searchQuery);
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
        $data = NotificationTemplate::orderBy('notification_templates.id')
        ->where('tittle', 'LIKE', '%' . $req . '%')
            ->orWhere('content', 'LIKE', '%' . $req . '%');

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
            'title' => 'required|unique:notification_templates,title',
            'content' => 'required',
            'role' => 'required',
            'action' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'title' => 'Judul Notifikasi',
            'content' => 'Konten',
            'role' => 'Role / Peran',
            'action' => 'Aksi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $model = new NotificationTemplate();
            $model->title = $request->title;
            $model->content = $request->content;
            if (str_contains($request->role, '0'))
                $model->all_role = 1;
            else
                $model->role_id = $request->role;
            $model->action_id = $request->action;
            $model->save();

            $this->logActivity->store("Membuat data notifikasi template $request->title.");

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

        $model = NotificationTemplate::find($id);
        $isUniqueTitle = $request->title && $request->title != $model->title ? '|unique:notification_templates,title' : '';

        $validator = Validator::make($request->all(), [
            'title' => 'required' . $isUniqueTitle,
            'content' => 'required',
            'role' => 'required',
            'action' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute telah digunakan.',
        ], [
            'title' => 'Judul Notifikasi',
            'content' => 'Konten',
            'role' => 'Role / Peran',
            'action' => 'Aksi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        try {
            $model->title = $request->title;
            $model->content = $request->content;
            if ($request->role == 0) {
                $model->role_id = null;
                $model->all_role = 1;
            } else {
                $model->role_id = $request->role;
                $model->all_role = 0;
            }
            $model->action_id = $request->action;
            $model->save();

            $this->logActivity->store("Memperbarui data notifikasi template.");

            $status = 'success';
            $message = 'Berhasil menyimpan perubahan';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan ' . $e;
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
            $model = NotificationTemplate::findOrFail($id);
            if ($model) {
                $this->logActivity->store("Menghapus data notifikasi template '$model->name'.");
                $model->delete();

                $status = 'success';
                $message = 'Berhasil menghapus data.';
            } else {
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
