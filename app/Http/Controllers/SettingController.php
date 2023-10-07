<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;

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
        //
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
