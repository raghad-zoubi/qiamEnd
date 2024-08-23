<?php


namespace App\Events;


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use GuzzleHttp\Client;

class FirebasePushNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $body;
    protected $data;
    protected $deviceToken;

    public function __construct($title, $body, $data = [], $deviceToken)
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
        $this->deviceToken = $deviceToken;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Add other channels like 'mail', 'sms', etc. if needed
    }

    public function toFirebasePushNotification($notifiable)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = [
            'to' => $this->deviceToken,
            'notification' => [
                'title' => $this->title,
                'body' => $this->body,
                'sound' => 'default'
            ],
            'data' => $this->data
        ];

        $headers = [
            'Authorization' => 'key=' . env('FIREBASE_SERVER_KEY'),
            'Content-Type' => 'application/json'
        ];

        $client = new Client();
        $response = $client->post($url, [
            'headers' => $headers,
            'body' => json_encode($fields)
        ]);

        return $response;
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
        ];
    }
}
