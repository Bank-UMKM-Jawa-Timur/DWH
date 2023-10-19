<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use App\Models\PembayaranPremi;
use Illuminate\Http\Request;

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
                $q->where('no_aplikasi', '=', $searchQuery)
                    ->orWhere('nobukti_pembayaran', '=', $searchQuery)
                    ->orWhere('tgl_bayar', '=', $searchQuery)
                    ->orWhere('total_premi', '=', $searchQuery)
                    ->orWhere('no_rek', '=', $searchQuery)
                    ->orWhere('no_pk', '=', $searchQuery)
                    ->orWhere('periode_bayar', '=', $searchQuery)
                    ->orWhere('total_periode', '=', $searchQuery);
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
