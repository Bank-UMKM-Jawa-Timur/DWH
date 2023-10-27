<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuanKlaimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('pages.pengajuan-klaim.index');
    }
    public function create(Request $request)
    {
        return view('pages.pengajuan-klaim.create');
    }
}
