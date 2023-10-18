<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelaporanPelunasanController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.pelaporan_pelunasan.index');
    }
    public function create(Request $request)
    {
        return view('pages.pelaporan_pelunasan.create');
    }
}
