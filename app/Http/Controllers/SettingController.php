<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\AppConfiguration;
use App\Models\User;
use App\Models\Vendor;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
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
        $param['title'] = 'Setting';
        $param['pageTitle'] = 'Setting';
        $data = AppConfiguration::first();
        $param['data'] = $data;

        return view('pages.setting.index', $param);
    }

    public function list($page_length = 5, $searchQuery, $searchBy)
    {
        $query = DB::table('app_configurations')->orderBy('id');
        if ($searchQuery && $searchBy === 'field') {
            $query->where(function ($s) use ($searchQuery) {
                $s->where('name', '=', $searchQuery)
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
        $data = DB::table('app_configurations')->orderBy('id')
        ->where('pusher_app_id', 'LIKE', '%' . $req . '%')
            ->orWhere('pusher_app_key', 'LIKE', '%' . $req . '%')
            ->orWhere('pusher_app_secret', 'LIKE', '%' . $req . '%')
            ->orWhere('pusher_cluster', 'LIKE', '%' . $req . '%')
            ->orWhere('los_host', 'LIKE', '%' . $req . '%')
            ->orWhere('los_api_host', 'LIKE', '%' . $req . '%')
            ->orWhere('los_asset_url', 'LIKE', '%' . $req . '%')
            ->orWhere('bio_interface_api_host', 'LIKE', '%' . $req . '%')
            ->orWhere('collection_api_host', 'LIKE', '%' . $req . '%')
            ->orWhere('microsoft_graph_client_id', 'LIKE', '%' . $req . '%')
            ->orWhere('microsoft_graph_client_secret', 'LIKE', '%' . $req . '%')
            ->orWhere('microsoft_graph_tenant_id', 'LIKE', '%' . $req . '%');

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
        $this->validate($request, [
            "pusher_app_id" => "required",
            "pusher_app_key" => "required",
            "pusher_app_secret" => "required",
            "pusher_cluster" => "required",
            "los_host" => "required",
            "los_api_host" => "required",
            "los_asset_url" => "required",
            "bio_interface_api_host" => "required",
            "collection_api_host" => "required",
            "microsoft_graph_client_id" => "required",
            "microsoft_graph_client_secret" => "required",
            "microsoft_graph_tenant_id" => "required"
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            "pusher_app_id" => "Pusher App ID",
            "pusher_app_key" => "Pusher App Key",
            "pusher_app_secret" => "Pusher App Secret",
            "pusher_cluster" => "Pusher Cluster",
            "los_host" => "LOS Host",
            "los_api_host" => "LOS API Host",
            "los_asset_url" => "LOS Asset URL",
            "bio_interface_api_host" => "Bio Interface API Host",
            "collection_api_host" => "Collection API Host",
            "microsoft_graph_client_id" => "Microsoft Graph Client ID",
            "microsoft_graph_client_secret" => "Microsoft Graph Client Secret",
            "microsoft_graph_tenant_id" => "Microsoft Graph Tenant ID"
        ]);

        try{
            DB::beginTransaction();

            $data = AppConfiguration::first();
            $data->pusher_app_id = $request->pusher_app_id;
            $data->pusher_app_key = $request->pusher_app_key;
            $data->pusher_app_secret = $request->pusher_app_secret;
            $data->pusher_cluster = $request->pusher_cluster;
            $data->los_host = $request->los_host;
            $data->los_api_host = $request->los_api_host;
            $data->los_asset_url = $request->los_asset_url;
            $data->bio_interface_api_host = $request->bio_interface_api_host;
            $data->collection_api_host = $request->collection_api_host;
            $data->microsoft_graph_client_id = $request->microsoft_graph_client_id;
            $data->microsoft_graph_client_secret = $request->microsoft_graph_client_secret;
            $data->microsoft_graph_tenant_id = $request->microsoft_graph_tenant_id;
            $data->updated_at = now();
            $data->save();

            DB::commit();

            Alert::success('Berhasil', 'Berhasil mengubah setting.');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Gagal', $e->getMessage());
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Gagal', $e->getMessage());
        } finally {
            return back();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
