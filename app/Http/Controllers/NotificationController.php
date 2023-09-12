<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Role;
use App\Models\User;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
                                    'notifications.extra',
                                    'notifications.read',
                                    'notifications.created_at',
                                    'notifications.updated_at',
                                    'nt.title',
                                    'nt.content',
                                    'nt.action_id',
                                    'nt.role_id',
                                )
                                ->join('notification_templates AS nt', 'nt.id', 'notifications.template_id')
                                ->where('notifications.user_id', \Session::get(config('global.user_id_session')))
                                ->orderBy('notifications.read')
                                ->orderBy('notifications.created_at', 'DESC')
                                ->get();
            $param['total_belum_dibaca'] = Notification::select('notifications.id')
                                            ->join('users AS u', 'u.id', 'notifications.user_id')
                                            ->where('u.id', \Session::get(config('global.user_id_session')))
                                            ->where('notifications.read', false)
                                            ->count();
            return view('pages.notifikasi.index', $param);
        } catch (\Exception $e) {
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

    public function listJson()
    {
        $status = '';
        $message = '';
        $data = [];

        try {
            $data = Notification::select(
                                    'notifications.id',
                                    'notifications.user_id',
                                    'notifications.extra',
                                    'notifications.read',
                                    'notifications.created_at',
                                    'notifications.updated_at',
                                    'nt.title',
                                    'nt.content',
                                    'nt.action_id',
                                    'nt.role_id',
                                )
                                ->join('notification_templates AS nt', 'nt.id', 'notifications.template_id')
                                ->where('notifications.user_id', \Session::get(config('global.user_id_session')))
                                ->where('notifications.read', false)
                                ->orderBy('notifications.read')
                                ->orderBy('notifications.created_at', 'DESC')
                                ->get();

            $status = 'success';
            $message = 'Berhasil mengambil data';
            } catch (\Exception $e) {
                $status = 'failed';
                $message = 'Terjadi kesalahan ' . $e;
            } catch (\Illuminate\Database\QueryException $e) {
                $status = 'failed';
                $message = 'Terjadi kesalahan pada database';
            } catch (\Throwable $th) {
                $status = 'failed';
                $message = 'Terjadi kesalahan ' . $th;
            }  finally {
            $response = [
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ];

            return response()->json($response);
        }
    }

    public function detail($id)
    {
        $status = '';
        $message = '';
        $data = [];
        $total_belum_dibaca = null;

        try {
            $data = Notification::select(
                                    'notifications.id',
                                    'notifications.user_id',
                                    'notifications.extra',
                                    'notifications.read',
                                    'notifications.created_at',
                                    'notifications.updated_at',
                                    'nt.title',
                                    'nt.content',
                                    'nt.action_id',
                                    'nt.role_id',
                                )
                                ->join('notification_templates AS nt', 'nt.id', 'notifications.template_id')
                                ->where('notifications.id', $id)
                                ->first();
            $data->read = 1;
            $data->save();
            $total_belum_dibaca = Notification::select('notifications.id')
                                            ->join('users AS u', 'u.id', 'notifications.user_id')
                                            ->where('u.id', \Session::get(config('global.user_id_session')))
                                            ->where('notifications.read', false)
                                            ->count();

            $status = 'success';
            $message = 'Berhasil mengambil data';
            } catch (\Exception $e) {
                $status = 'failed';
                $message = 'Terjadi kesalahan ' . $e;
            } catch (\Illuminate\Database\QueryException $e) {
                $status = 'failed';
                $message = 'Terjadi kesalahan pada database';
            } catch (\Throwable $th) {
                $status = 'failed';
                $message = 'Terjadi kesalahan ' . $th;
            }  finally {
            $response = [
                'status' => $status,
                'message' => $message,
                'data' => $data,
                'total_belum_dibaca' => $total_belum_dibaca,
            ];

            return response()->json($response);
        }
    }

    public function send($action_id, $kreditId)
    {
        try {
            DB::beginTransaction();

            // get notification template
            $template = NotificationTemplate::where('action_id', $action_id)->get();

            // get roles
            $roles = Role::pluck('id');

            // get kredit
            $kredit = Kredit::find($kreditId);

            // get user who will be sended the notification
            foreach ($template as $key => $value) {
                // get kode cabang
                if (!$value->role_id && $value->all_role) {
                    // retrieve from api
                    $host = config('global.los_api_host');
                    $apiURL = $host . '/kkb/get-data-users-cabang/' . $kredit->kode_cabang;

                    $headers = [
                        'token' => config('global.los_api_token')
                    ];

                    $responseBody = null;

                    try {
                        $response = Http::withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

                        $statusCode = $response->status();
                        $responseBody = json_decode($response->getBody(), true);
                        $user = $responseBody;

                        if ($user) {
                            foreach ($user as $key => $item) {
                                $createNotification = new Notification();
                                $createNotification->kredit_id = $kreditId;
                                $createNotification->template_id = $value->id;
                                $createNotification->user_id = $item['id'];
                                $createNotification->save();
                            }
                        }
                    } catch (\Illuminate\Http\Client\ConnectionException $e) {
                        // return $e->getMessage();
                    }
                }
                else {
                    $arrRole = explode(',', $value->role_id);
                    $user = User::where('kode_cabang', $kredit->kode_cabang)
                                ->whereIn('role_id', $arrRole)
                                ->get();

                    foreach ($user as $key => $item) {
                        $createNotification = new Notification();
                        $createNotification->kredit_id = $kreditId;
                        $createNotification->template_id = $value->id;
                        $createNotification->user_id = $item->id;
                        $createNotification->save();
                    }
                }
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
        } catch(\Illuminate\Database\QueryException $e) {
            DB::rollBack();
        }
    }

    public function sendWithExtra($action_id, $kreditId, $extra)
    {
        try {
            DB::beginTransaction();

            // get notification template
            $template = NotificationTemplate::where('action_id', $action_id)->get();

            // get roles
            $roles = Role::pluck('id');

            // get kredit
            $kredit = Kredit::find($kreditId);

            // get user who will be sended the notification
            foreach ($template as $key => $value) {
                // get kode cabang
                if (!$value->role_id && $value->all_role) {
                    // retrieve from api
                    $host = config('global.los_api_host');
                    $apiURL = $host . '/kkb/get-data-users-cabang/' . $kredit->kode_cabang;

                    $headers = [
                        'token' => config('global.los_api_token')
                    ];

                    $responseBody = null;

                    try {
                        $response = Http::withHeaders($headers)->withOptions(['verify' => false])->get($apiURL);

                        $statusCode = $response->status();
                        $responseBody = json_decode($response->getBody(), true);
                        $user = $responseBody;

                        if ($user) {
                            foreach ($user as $key => $item) {
                                $createNotification = new Notification();
                                $createNotification->kredit_id = $kreditId;
                                $createNotification->template_id = $value->id;
                                $createNotification->user_id = $item['id'];
                                $createNotification->extra = $extra;
                                $createNotification->save();
                            }
                        }
                    } catch (\Illuminate\Http\Client\ConnectionException $e) {
                        // return $e->getMessage();
                    }
                }
                else {
                    $arrRole = explode(',', $value->role_id);
                    $user = User::where('kode_cabang', $kredit->kode_cabang)
                                ->whereIn('role_id', $arrRole)
                                ->orWhereIn('role_id', $arrRole)
                                ->get();

                    foreach ($user as $key => $item) {
                        $createNotification = new Notification();
                        $createNotification->kredit_id = $kreditId;
                        $createNotification->template_id = $value->id;
                        $createNotification->user_id = $item->id;
                        $createNotification->extra = $extra;
                        $createNotification->save();
                    }
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
