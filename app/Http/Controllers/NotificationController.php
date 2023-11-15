<?php

namespace App\Http\Controllers;

use App\Models\KKB;
use App\Mail\SendMail;
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
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;  
use PHPMailer\PHPMailer\Exception;

class NotificationController extends Controller
{
    private $losHeaders;
    private $losHost;

    function __construct() {
        $bearerToken = \Session::get(config('global.user_token_session'));
        $this->losHost = config('global.los_api_host');
        $this->losHeaders = [
            'token' => config('global.los_api_token')
        ];
    }

    public function index(Request $request)
    {
        try {
            $param['title'] = 'Notifikasi';
            $param['pageTitle'] = 'Notifikasi';
            $limit = $request->has('page_length') ? $request->page_length : 5;

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
                                ->when($request->get('query'), function($query) use ($request) {
                                    return $query->where('nt.title', 'like', '%'.$request->get('query').'%')
                                        ->orWhere('nt.content', 'like', '%'.$request->get('query').'%');
                                })
                                ->orderBy('notifications.read')
                                ->orderBy('notifications.created_at', 'DESC')
                                ->paginate($limit);

            $param['total'] = Notification::select('notifications.id')
                                            ->where('notifications.user_id', \Session::get(config('global.user_id_session')))
                                            ->count();
            $param['total_belum_dibaca'] = Notification::select('notifications.id')
                                            ->where('notifications.user_id', \Session::get(config('global.user_id_session')))
                                            ->where('notifications.read', false)
                                            ->count();
                                            
            return view('pages.notifikasi.index', $param);
        } catch (\Exception $e) {
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

    public function getDataPO($pengajuan_id) {
        try {
            $token = \Session::get(config('global.user_token_session'));
            $this->losHeaders['Authorization'] = "Bearer $token";
            $apiURL = $this->losHost . '/kkb/get-data-pengajuan-by-id/' . $pengajuan_id;

            $response = Http::timeout(3)->withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody) {
                if (array_key_exists('no_po', $responseBody) && array_key_exists('nama', $responseBody))
                    return $responseBody;
                else
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Not found',
                    ]);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
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
                                            ->where('notifications.user_id', \Session::get(config('global.user_id_session')))
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

    public function getDataImportById($import_id) {
        $data = DB::select("SELECT * FROM imported_data WHERE id=$import_id LIMIT 1");
        
        return $data;
    }

    public function send($action_id, $kreditId)
    {
        try {
            DB::beginTransaction();
            $kkb = KKB::select('id', 'kredit_id', 'user_id')
                        ->where('kredit_id', $kreditId)
                        ->first();
            $kredit = Kredit::find($kkb->kredit_id);
            $is_import = $kredit->imported_data_id != null;
            $user_receiver_id = $kkb ? $kkb->user_id : null;
            $tanggal = date('d-m-Y');
            $res_email = null;

            // get notification template
            $template = NotificationTemplate::where('action_id', $action_id)->get();

            // get user who will be sended the notification
            foreach ($template as $key => $value) {
                // get kode cabang
                if (!$value->role_id && $value->all_role) {
                    // send to all role
                    $user = User::where('role_id', 3)->first();

                    if ($user) {
                        $dataPO = null;
                        $no_po = 'undifined';
                        $nama_debitur = 'undifined';
                        if ($is_import) {
                            $dataPO = $this->getDataImportById($kredit->imported_data_id);
                            if (count($dataPO) > 0)
                                $dataPO = $dataPO[0];
                            $no_po = 'Import Data Google Spreadsheet';
                            $nama_debitur = $dataPO->name;
                        }
                        else {
                            $dataPO = $this->getDataPO($kredit->pengajuan_id);
                            if (!array_key_exists('status', $dataPO)) {
                                if (array_key_exists('id_pengajuan', $dataPO)) {
                                    $no_po = $dataPO['no_po'];
                                    $nama_debitur = $dataPO['nama'];
                                }
                            }
                        }

                        $res_email = $this->sendEmail($user->email,  [
                            'title' => $value->title,
                            'tanggal' => $tanggal,
                            'no_po' => $no_po,
                            'nama_debitur' => $nama_debitur,
                            'to' => $user->name,
                            'body' => $value->content,
                        ]);
                    }

                    $createNotification = new Notification();
                    $createNotification->kredit_id = $kreditId;
                    $createNotification->template_id = $value->id;
                    $createNotification->user_id = $user_receiver_id;
                    $createNotification->save();
                }
                else {
                    // send to selected role
                    $arrRole = explode(',', $value->role_id);
                    if (in_array(3, $arrRole)) {
                        $user = User::where('role_id', 3)->first();

                        if ($user) {
                            $dataPO = null;
                            $no_po = 'undifined';
                            $nama_debitur = 'undifined';
                            if ($is_import) {
                                $dataPO = $this->getDataImportById($kredit->imported_data_id);
                                if (count($dataPO) > 0)
                                    $dataPO = $dataPO[0];
                                $no_po = 'Import Data Google Spreadsheet';
                                $nama_debitur = $dataPO->name;
                            }
                            else {
                                $dataPO = $this->getDataPO($kredit->pengajuan_id);
                                if (!array_key_exists('status', $dataPO)) {
                                    if (array_key_exists('id_pengajuan', $dataPO)) {
                                        $no_po = $dataPO['no_po'];
                                        $nama_debitur = $dataPO['nama'];
                                    }
                                }
                            }

                            $res_email = $this->sendEmail($user->email,  [
                                'title' => $value->title,
                                'tanggal' => $tanggal,
                                'no_po' => $no_po,
                                'nama_debitur' => $nama_debitur,
                                'to' => $user->name,
                                'body' => $value->content,
                            ]);
                        }
                    }
                    if (in_array(2, $arrRole)) {
                        $createNotification = new Notification();
                        $createNotification->kredit_id = $kreditId;
                        $createNotification->template_id = $value->id;
                        $createNotification->user_id = $user_receiver_id;
                        $createNotification->save();
                    }
                }
            }
            DB::commit();

            return $res_email;
        } catch(\Exception $e) {
            return $e->getMessage();
            DB::rollBack();
        } catch(\Illuminate\Database\QueryException $e) {
            return $e->getMessage();
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

            $token = \Session::get(config('global.user_token_session'));
            $this->losHeaders['Authorization'] = "Bearer $token";

            // get user who will be sended the notification
            foreach ($template as $key => $value) {
                // get kode cabang
                if (!$value->role_id && $value->all_role) {
                    // retrieve from api
                    $apiURL = $this->losHost . '/kkb/get-data-users-cabang/' . $kredit->kode_cabang;
                    $responseBody = null;

                    try {
                        $response = Http::withHeaders($this->losHeaders)->withOptions(['verify' => false])->get($apiURL);

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

    public function sendEmail($mail_to, $mail_body) {
        $status = '';
        $message = '';

        try {
            // cabang sample email = 'cabangsurabaya@bankumkm.id'
            Mail::to($mail_to)->send(new SendMail($mail_body));

            $status = 'success';
            $message = 'Berhasil mengirim email';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Gagal mengirim email. '.$e->getMessage();
        } finally {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'mail_to' => $mail_to,
                'mail_body' => $mail_body,
            ]);
        }
    }
}
