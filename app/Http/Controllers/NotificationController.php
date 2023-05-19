<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
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
