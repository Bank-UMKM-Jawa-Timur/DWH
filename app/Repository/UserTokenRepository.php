<?php

namespace App\Repository;

use Illuminate\Support\Facades\Http;

class UserTokenRepository {
    public static function userByToken($token) : ?object 
    {
        $host = env('LOS_API_HOST');
        $apiURL = $host . '/profile';
        $headers = [
            'Authorization' => "Bearer $token",
        ];
                // get cookies
        $response = Http::withHeaders($headers)
                        ->withOptions(['verify' => false])
                        ->get($apiURL);
        
        return $response->object();
    }
}