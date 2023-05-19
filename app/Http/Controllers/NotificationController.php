<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $param['title'] = 'Notifikasi';
            $param['pageTitle'] = 'Notifikasi';

            $param['data'] = Notification::select(
                                    'notifications.id',
                                    'notifications.user_id',
                                    'notifications.read',
                                    'notifications.created_at',
                                    'notifications.updated_at',
                                    'nt.title',
                                    'nt.content',
                                    'nt.action_id',
                                    'nt.role_id',
                                )
                                ->join('notification_templates AS nt', 'nt.id', 'notifications.template_id')
                                ->where('notifications.user_id', Auth::user()->id)
                                ->orderBy('notifications.created_at', 'DESC')
                                ->get();
            $param['total_belum_dibaca'] = Notification::select('notifications.id')
                                            ->join('users AS u', 'u.id', 'notifications.user_id')
                                            ->where('u.id', Auth::user()->id)
                                            ->count();

            return view('pages.notifikasi.index', $param);
        } catch (\Exception $e) {
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

    public function send($action_id)
    {
        try {
            DB::beginTransaction();

            // get notification template
            $notif = NotificationTemplate::where('action_id', $action_id)->get();

            // get user who will be sended the notification
            foreach ($notif as $key => $value) {
                // get kode cabang
                $user = User::where('role_id', $value->role_id)->get();
                foreach ($user as $key => $item) {
                    $createNotification = new Notification();
                    $createNotification->template_id = $value->id;
                    $createNotification->user_id = $item->id;
                    $createNotification->save();
                }
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
        } catch(\Illuminate\Database\QueryException $e) {
            DB::rollBack();
        }
    }
}
