<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LogActivitesController extends Controller
{
    private $losHeaders;
    private $losHost;

    function __construct() {
        $bearerToken = \Session::get(config('global.user_token_session'));
        $this->losHost = config('global.los_api_host');
        $this->losHeaders = [
            'token' => config('global.los_api_token')
        ];
    }
    public function index(Request $request)
    {
        $param['title'] = 'Log Aktivitas';
        $param['pageTitle'] = 'Log Aktivitas';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);

        $param['data'] = $data;
        $param['page_length'] = $page_length;

        return view('pages.log_aktivitas.index', $param);
    }
    public function list($page_length = 5, $searchQuery, $searchBy)
    {
        $data = LogActivity::select(
            'log_activities.*',
            'u.email',
            'u.role_id'
        )
        ->leftJoin('users AS u', 'u.id', 'log_activities.user_id')
        ->orderBy('log_activities.created_at', 'DESC');

        if ($searchQuery && $searchBy === 'field') {
            $data->where(function ($q) use ($searchQuery) {
                // $q->where('u.nip', '=', $searchQuery)->orWhere('log_activities.content', '=', $searchQuery);
                $q->where('log_activities.content', '=', $searchQuery);
            });
        }
        if (is_numeric($page_length))
            $data = $data->paginate($page_length);
        else
            $data = $data->get();

        $token = \Session::get(config('global.user_token_session'));
        $this->losHeaders['Authorization'] = "Bearer $token";
        
        foreach ($data as $key => $value) {
            if ($value->role_id != 3) {
                // role bukan vendor
                // retrieve from api
                $apiURL = $this->losHost . '/kkb/get-data-users-by-id/' . $value->user_id;
                $responseBody = null;
                $user = null;
    
                try {
                    $response = Http::withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

                    $statusCode = $response->status();
                    $responseBody = json_decode($response->getBody(), true);
                    $user = $responseBody;
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // return $e->getMessage();
                }
                $value->user = $user;
            }
        }
        return $data;
    }

    public function search($req, $page_length = 5)
    {
        $data = LogActivity::orderBy('log_activities.created_at', 'DESC')
        ->where('content', 'LIKE', '%' . $req . '%');

        if (is_numeric($page_length))
            $data = $data->paginate($page_length);
        else
            $data = $data->get();
        return $data;
    }

    public function store($content)
    {
        $token = \Session::get(config('global.user_token_session'));
        $newActivity = new LogActivity();
        $newActivity->user_id = $token ? \Session::get(config('global.user_id_session')) : Auth::user()->id;
        $newActivity->content = $content;

        $newActivity->save();
    }
}
