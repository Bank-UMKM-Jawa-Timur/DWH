<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanKlaimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_length = $request->page_length ? $request->page_length : 5;
        $data = DB::table('pengajuan_klaim');
        if ($request->has('search')) {
            $search = $request->get('search');
            $data = $data->where('no_rek', 'LIKE', "%$search%")
                ->orWhere('no_aplikasi', 'LIKE', "%$search%")
                ->orWhere('no_klaim', 'LIKE', "%$search%");
        }
        $data = $data->orderBy('no_aplikasi')->paginate($page_length);
        return view('pages.pengajuan-klaim.index', compact('data'));
    }
    public function create(Request $request)
    {
        $dataNoRek = DB::table('asuransi')->orderBy('no_aplikasi')->groupBy('no_aplikasi')->get();
        return view('pages.pengajuan-klaim.create', compact('dataNoRek'));
    }
}
