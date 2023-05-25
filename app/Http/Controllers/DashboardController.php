<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kredit;
use App\Models\Target;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $param['title'] = 'Dashboard';
            $param['pageTitle'] = 'Dashboard';
            $user = User::select(
                        'users.id',
                        'users.role_id',
                        'r.name AS role_name',
                    )
                    ->join('roles AS r', 'r.id', 'users.role_id')
                    ->where('users.id', Auth::user()->id)
                    ->first();
            $param['documentCategories'] = DocumentCategory::select('id', 'name')->whereNotIn('name', ['Bukti Pembayaran', 'Penyerahan Unit'])->orderBy('name', 'DESC')->get();
            $param['data'] = Kredit::select(
                        'kredits.id',
                        'kredits.pengajuan_id',
                        'kredits.kode_cabang',
                        'kkb.id AS kkb_id',
                        'kkb.tgl_ketersediaan_unit',
                        'kkb.id_tenor_imbal_jasa',
                        \DB::raw("(SELECT COUNT(id) FROM document_categories) AS total_doc_requirement"),
                        \DB::raw('COALESCE(COUNT(d.id), 0) AS total_file_uploaded'),
                        \DB::raw('CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) AS total_file_confirmed'),
                        // \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < COALESCE(COUNT(d.id), 0), 'process', 'done') AS status"),
                        \DB::raw("IF (CAST(COALESCE(SUM(d.is_confirm), 0) AS UNSIGNED) < (SELECT COUNT(id) FROM document_categories), 'process', 'done') AS status"),
                    )
                    ->join('kkb', 'kkb.kredit_id', 'kredits.id')
                    ->leftJoin('documents AS d', 'd.kredit_id', 'kredits.id')
                    ->groupBy([
                        'kredits.id',
                        'kredits.pengajuan_id',
                        'kredits.kode_cabang',
                        'kkb.id_tenor_imbal_jasa',
                        'kkb.id',
                        'kkb.tgl_ketersediaan_unit',
                        ])
                        ->orderBy('total_file_uploaded')
                        ->orderBy('total_file_confirmed')
                        ->paginate(5);
                        
            $param['role'] = $user->role_name;
            $param['total_cabang'] = User::where('role_id', 2)->count();
            $param['total_vendor'] = User::where('role_id', 3)->count();
            $target = Target::where('is_active', 1)->pluck('total_unit');
            $param['target'] = count($target) ? $target->first() : 0;
            if (Auth::user()->role_id != 3) {
                $param['total_kkb_done'] = Kredit::join('documents AS d', 'd.kredit_id', 'kredits.id')
                                        ->where('d.document_category_id', 2)
                                        ->count();
            }

            if (Auth::user()->role_id != 3)
                $param['total_pengguna'] = User::count();

            return view('pages.home', $param);
        } catch (\Exception $e) {
            return redirect('/dashboard')->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/dashboard')->withError('Terjadi kesalahan pada database');
        }
    }

    public function getRoleName()
    {
        $user = User::select(
            'users.id',
            'users.role_id',
            'r.name AS role_name',
        )
        ->join('roles AS r', 'r.id', 'users.role_id')
        ->where('users.id', Auth::user()->id)
        ->first();

        return $user->role_name;
    }
}
