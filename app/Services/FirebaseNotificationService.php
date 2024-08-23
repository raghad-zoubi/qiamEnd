<?php


namespace App\Services;




use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseNotificationService
{
    public function sendNotification($deviceToken, $title, $body, $data = [])
    {
        $messaging = Firebase::messaging();

        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification)
            ->withData($data);

        return $messaging->send($message);
    }
}
