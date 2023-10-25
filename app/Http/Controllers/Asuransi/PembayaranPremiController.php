<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use App\Models\PembayaranPremi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranPremiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param['title'] = 'Pembayaran Premi';
        $param['pageTitle'] = 'Pembayaran Premi';
        $page_length = $request->page_length ? $request->page_length : 5;

        $searchQuery = $request->query('query');
        $searchBy = $request->query('search_by');

        $data = $this->list($page_length, $searchQuery, $searchBy);
        $param['data'] = $data;
        $param['page_length'] = $page_length;

        return view('pages.pembayaran_premi.index', $param);
    }

    public function list($page_length = 5 , $searchQuery, $searchBy)
    {
        $query = PembayaranPremi::
                        orderBy('no_aplikasi');
        if ($searchQuery && $searchBy === 'field') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('no_aplikasi', 'like', '%' . $searchQuery . '%')
                    ->orWhere('nobukti_pembayaran', 'like', '%' . $searchQuery . '%')
                    ->orWhere('tgl_bayar', 'like', '%' . $searchQuery . '%')
                    ->orWhere('total_premi', 'like', '%' . $searchQuery . '%')
                    ->orWhere('no_rek', 'like', '%' . $searchQuery . '%')
                    ->orWhere('no_pk', 'like', '%' . $searchQuery . '%')
                    ->orWhere('periode_bayar', 'like', '%' . $searchQuery . '%')
                    ->orWhere('total_periode', 'like', '%' . $searchQuery . '%');
            });
        }
        if (is_numeric($page_length)) {
            $data = $query->paginate($page_length);
        } else {
            $data = $query->get();
        }

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $param['noAplikasi'] = DB::table('asuransi')->select('asuransi.no_aplikasi','jenis.jenis', 'asuransi.id')
        ->join('mst_jenis_asuransi as jenis', 'asuransi.jenis_asuransi_id', '=', 'jenis.id')
        ->where('status', 'onprogress')->groupBy('no_aplikasi')
        ->get();
        $param['jenisAsuransi'] = DB::table('asuransi')->select('asuransi.*', 'jenis.jenis')
        ->join('mst_jenis_asuransi as jenis', 'asuransi.jenis_asuransi_id', '=', 'jenis.id')
        ->where('status', 'onprogress')
        ->get();

        // return $param['jenisAsuransi'];


        return view('pages.pembayaran_premi.create', $param);
    }

    public function getsAsuransiByNoAplikasi($jenis){
        // $jenisArray = explode(',', $jenis);
        $data = DB::table('asuransi')->select('asuransi.*', 'jenis.jenis')
        ->where('jenis.jenis', $jenis)
        ->join('mst_jenis_asuransi as jenis', 'asuransi.jenis_asuransi_id', '=', 'jenis.id')
        ->where('status', 'onprogress')
        ->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function getJenisByNoAplikasi($apk){
        $jenis = DB::table('asuransi')->select('asuransi.*', 'jenis.jenis')
        ->where('jenis.jenis', $apk)
        ->join('mst_jenis_asuransi as jenis', 'asuransi.jenis_asuransi_id', '=', 'jenis.id')
        ->where('status', 'onprogress')
        ->get();

        return response()->json([
            'dataJenis' => $jenis
        ]);
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
