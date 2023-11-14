<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $host;
    private $headers;
    public $user_id;
    public $user_nip;

    function __construct()
    {
        $this->host = env('LOS_API_HOST');
        $this->headers = [
            'token' => env('LOS_API_TOKEN')
        ];
        $this->getLoginSession();
    }

    public function getLoginSession() {
        $user = Session::get(config('global.auth_session'));
        if ($user) {
            if (array_key_exists('status', $user)) {
                if ($user['status'] == 'berhasil') {
                    $this->user_id = $user['id'];
                    $this->user_nip = $user['data']['nip'];
                }
                return $user;
            }
        }

        return [
            'status' => 'gagal',
            'message' => 'Session tidak ditemukan.',
        ];
    }

    public function serverSessionCheck() {
        $failed_response = [
            'status' => 'gagal',
            'message' => 'Gagal mengambil data'
        ];

        if ($this->host) {
            $apiURL = $this->host . "/get-session-check/$this->user_id";
            $token = \Session::get(config('global.user_token_session'));
            $this->headers['Authorization'] = $token;

            try {
                $response = Http::timeout(3)
                                ->withHeaders($this->headers)
                                ->withOptions(['verify' => false])
                                ->get($apiURL);
                $responseBody = json_decode($response->getBody(), true);

                if ($responseBody) {
                    if (array_key_exists('status', $responseBody)) {
                        if ($responseBody['status'] == 'sukses') {
                            return $responseBody;
                        }
                    }
                }
                return $failed_response;
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $failed_response = [
                    'status' => 'gagal',
                    'message' => $e->getMessage(),
                ];
                return $failed_response;
            }
        }
        else {
            $failed_response = [
                'status' => 'gagal',
                'message' => 'Host api belum diatur'
            ];

            return $failed_response;
        }
    }

    public function getAllCabang() {
        $failed_response = [
            'status' => 'gagal',
            'message' => 'Gagal mengambil data'
        ];

        if ($this->host) {
            $apiURL = $this->host . '/v1/get-cabang';
            $token = \Session::get(config('global.user_token_session'));
            $this->headers['Authorization'] = $token;
            
            try {
                $response = Http::timeout(3)
                                ->withHeaders($this->headers)
                                ->withOptions(['verify' => false])
                                ->get($apiURL);
                $responseBody = json_decode($response->getBody(), true);

                if (array_key_exists('status', $responseBody)) {
                    if ($responseBody['status'] == 'berhasil') {
                        return $responseBody;
                    }
                    else
                        return $responseBody;
                }
                else
                    return $failed_response;
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $failed_response = [
                    'status' => 'gagal',
                    'message' => $e->getMessage(),
                ];
                return $failed_response;
            }
        }
        else {
            $failed_response = [
                'status' => 'gagal',
                'message' => 'Host api belum diatur'
            ];

            return $failed_response;
        }
    }

    public function getStafByCabang($kode_cabang) {
        // retrieve from api
        $host = config('global.los_api_host');
        $apiURL = $host . '/kkb/get-data-staf-cabang/' . $kode_cabang;
        $token = \Session::get(config('global.user_token_session'));
        $this->headers['Authorization'] = $token;
        $responseBody = null;

        try {
            $response = Http::withHeaders($this->headers)->withOptions(['verify' => false])->get($apiURL);

            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);
            return $responseBody;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
