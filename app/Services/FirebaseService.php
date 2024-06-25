<?php

namespace App\Services;
namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $firebase;

    public function __construct()
    {
        $serviceAccountPath = base_path(
            'app\Http\Controllers\alqyam-1d894-firebase-adminsdk-jrajn-ba538d23d5.json'); // Adjust this path

        if (!file_exists($serviceAccountPath) || !is_readable($serviceAccountPath)) {
            throw new \Exception('The service account file is not readable or does not exist: ' . $serviceAccountPath);
        }

        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccountPath)
            ->withDatabaseUri('https://alqyam-1d894-default-rtdb.firebaseio.com/');
    }

    public function sendNotification($deviceToken, $title, $body)
    {
        $messaging = $this->firebase->createMessaging();

        $message = [
            'token' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
        ];

        $messaging->send($message);
    }
}
