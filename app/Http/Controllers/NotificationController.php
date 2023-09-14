<?php

namespace App\Http\Controllers;

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

    public function sendEmail(Request $request) {
        $mail_to = 'mkhalil26122000@gmail.com';
        // return [
        //     'transport' => 'smtp',
        //     'host' => env('MAIL_HOST', 'smtp.office365.com'),
        //     'port' => (int)env('MAIL_PORT', 587),
        //     'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        //     'username' => env('MAIL_USERNAME'),
        //     'password' => env('MAIL_PASSWORD'),
        //     'timeout' => null,
        // ];
        $testMailData = [
            'title' => 'Test Email From no-reply.kkb@dwh.develop.bankumkm.id',
            'body' => $request->body
        ];

        Mail::to($mail_to)->send(new SendMail($testMailData));

        dd('Success! Email has been sent successfully.');
        //Create an instance; passing `true` enables exceptions
        // $mail = new PHPMailer(true);

        // try {
        //     //Server settings
        //     $mail->SMTPDebug = 0;                      //Enable verbose debug output
        //     $mail->isSMTP();                                            //Send using SMTP
        //     $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        //     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        //     $mail->Username   = 'no-reply.kkb@dwh.bankumkm.id';                     //SMTP username
        //     $mail->Password   = 'J4tim1!!';                               //SMTP password
        //     $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        //     $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //     //Recipients
        //     $mail->setFrom('no-reply.kkb@dwh.bankumkm.id', 'Mailer');
        //     $mail->addAddress($mail_to, 'Khalil');     //Add a recipient
        //     $mail->addReplyTo('info@example.com', 'Information');
        //     $mail->addCC('cc@example.com');
        //     $mail->addBCC('bcc@example.com');

        //     //Attachments
        //     // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //     // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //     //Content
        //     $mail->isHTML(true);                                  //Set email format to HTML
        //     $mail->Subject = 'Here is the subject';
        //     $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        //     $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        //     $mail->send();
        //     return 'Message has been sent';
        // } catch (\Exception $e) {
        //     return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        // }
        // $mail = new PHPMailer(true);     // Passing `true` enables exceptions
 
        // try {
        //     // Email server settings
        //     $mail->SMTPDebug = 0;
        //     $mail->isSMTP();
        //     $mail->SMTPKeepAlive = true;  
        //     $mail->Host = env('MAIL_HOST', 'smtp.office365.com');             //  smtp host
        //     $mail->Username = env('MAIL_USERNAME');   //  sender username
        //     $mail->Password = env('MAIL_PASSWORD');       // sender password
        //     $mail->SMTPSecure = 'tls';                  // encryption - ssl/tls
        //     $mail->IsSMTP();
        //     $mail->SMTPAuth = true;
        //     $mail->Port = '465';                          // port - 587/465
 
        //     $mail->setFrom(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
        //     $mail->addAddress($mail_to);
        //     $mail->addCC($mail_to);
        //     $mail->addBCC($mail_to);
 
        //     $mail->addReplyTo(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
 
        //     // if(isset($_FILES['emailAttachments'])) {
        //     //     for ($i=0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
        //     //         $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
        //     //     }
        //     // }
 
 
        //     $mail->isHTML(true);                // Set email content format to HTML
 
        //     // $mail->Subject = $mail_toSubject;
        //     $mail->Body    = $request->body;
 
        //     $mail->AltBody = $request->body;
        //     // return ['data' => $mail];
            
        //     if( !$mail->send() ) {
        //         return $mail->ErrorInfo;
        //     }
            
        //     else {
        //         return "Email has been sent.";
        //     }
        //     dd($mail);
 
        // } catch (\Exception $e) {
        //      return $e->getMessage();
        // }
    }
}
