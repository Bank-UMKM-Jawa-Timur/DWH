<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogActivitesController extends Controller
{
    public function index()
    {
        $param['title'] = 'Log Aktivitas';
        $param['pageTitle'] = 'Log Aktivitas';
        $param['data'] = LogActivity::select(
                            'log_activities.*',
                            'u.nip',
                            'u.email',
                        )
                        ->join('users AS u', 'u.id', 'log_activities.user_id')
                        ->orderBy('log_activities.created_at', 'DESC')
                        ->get();

        return view('pages.log_aktivitas.index', $param);
    }

    public function store($content)
    {
        $newActivity = new LogActivity();
        $newActivity->user_id = Auth::user()->id;
        $newActivity->content = $content;

        $newActivity->save();
    }
}
