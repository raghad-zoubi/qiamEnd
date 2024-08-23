<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FCMService
{
    public static function send($token, $notification)
    {
      // dd($token);
        try{
        Http::acceptJson()->withToken(config('fcm.token'))->post(
            'https://fcm.googleapis.com/fcm/send',
            [
                'to' => $token,
                'notification' => $notification,
            ]
        );
    }catch (\Exception $e) {


            throw new \Exception($e->getMessage());
        }
    }

}
