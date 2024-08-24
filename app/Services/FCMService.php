<?php

namespace App\Services;

use GuzzleHttp\Client;

class FCMService1
{
    protected $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    protected $serverKey;

    public function __construct()
    {
        $this->serverKey = env('FCM_SERVER_KEY');
    }

    /**
     * Send a notification via FCM
     *
     * @param  array  $tokens  List of device tokens
     * @param  string $title   Notification title
     * @param  string $body    Notification body
     * @param  array  $data    Additional data payload (optional)
     * @return void
     */
    public function sendNotification(array $tokens, string $title, string $body, array $data = [])
    {
        $client = new Client();

        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => 'default'
        ];

        $payload = [
            'registration_ids' => $tokens,  // Can also use 'to' => token for a single device
            'notification' => $notification,
            'data' => $data,
            'priority' => 'high',
        ];

        $headers = [
            'Authorization' => 'key=' . $this->serverKey,
            'Content-Type' => 'application/json',
        ];

        $response = $client->post($this->fcmUrl, [
            'headers' => $headers,
            'json' => $payload,
        ]);

        return $response->getStatusCode();
    }
}
