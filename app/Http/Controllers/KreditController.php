<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use App\Models\KKB;
use App\Models\Kredit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KreditController extends Controller
{
    private $logActivity;
    private $dashboardContoller;
    private $param;

    function __construct()
    {
        $this->logActivity = new LogActivitesController;
        $this->dashboardContoller = new DashboardController;
    }

    public function index()
    {
        $this->param['role'] = $this->dashboardContoller->getRoleName();
        $this->param['title'] = 'Kredit';
        $this->param['pageTitle'] = 'Kredit';
        $this->param['documentCategories'] = DocumentCategory::select('id', 'name')->orderBy('name', 'DESC')->get();
        $this->param['data'] = Kredit::select(
                            'kredits.*',
                            'kkb.id AS kkb_id',
                            'kkb.tgl_ketersediaan_unit',
                            'kkb.imbal_jasa',
                        )
                        ->join('kkb', 'kkb.kredit_id', 'kredits.id')
                        ->orderBy('kredits.id')
                        ->paginate(5);

        return view('pages.kredit.index', $this->param);
    }

    public function setTglKetersedianUnit(Request $request)
    {
        $status = '';
        $message = '';

        $validator = Validator::make($request->all(), [
            'id_kkb' => 'required',
            'date' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            'id_kkb' => 'Kredit',
            'date' => 'Tanggal ketersediaan unit',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        try {
            $kkb = KKB::where('id', $request->id_kkb)->first();
            $kkb->tgl_ketersediaan_unit = date('Y-m-d', strtotime($request->date));
            $kkb->save();

            $this->logActivity->store('Pengguna '.$request->name.' mengatur tanggal penyerahan unit.');

            $status = 'success';
            $message = 'Berhasil menyimpan data';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan';
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database';
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            return response()->json($response);
        }
    }
}
