<?php

namespace App\Http\Controllers\Asuransi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Alert;

class RegistrasiController extends Controller
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
        try {
            $page_length = $request->page_length ? $request->page_length : 5;
            $data = DB::table('asuransi');
            if ($request->has('q')) {
                $q = $request->get('q');
                $data = $data->where('nama_debitur', 'LIKE', "%$q%")
                            ->orWhere('no_aplikasi', 'LIKE', "%$q%")
                            ->orWhere('no_polis', 'LIKE', "%$q%")
                            ->orWhere('tgl_polis', 'LIKE', "%$q%")
                            ->orWhere('tgl_rekam', 'LIKE', "%$q%");
            }
            $data = $data->orderBy('no_aplikasi')->paginate($page_length);

            return view('pages.asuransi-registrasi.index', compact('data'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Terjadi kesalahan pada database. '.$e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.asuransi-registrasi.create');
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
