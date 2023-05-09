<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $param['title'] = 'Dashboard';
        $param['pageTitle'] = 'Dashboard SuperAdmin';
        $user = User::select(
                    'users.id',
                    'users.role_id',
                    'r.name AS role_name',
                )
                ->join('roles AS r', 'r.id', 'users.role_id')
                ->where('users.id', Auth::user()->id)
                ->first();
        $param['role'] = $user->role_name;

        return view('pages.home', $param);
    }
}
