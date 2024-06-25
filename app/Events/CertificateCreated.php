<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CertificateCreated
{

    use Dispatchable, SerializesModels;

    public $user;
    public $courseDetails;

    public function __construct(User $user, $courseDetails)
    {
        $this->user = $user;
        $this->courseDetails = $courseDetails;
    }

    public function handle()
    {
        // قم بتوليد الشهادة هنا باستخدام بيانات المستخدم وتفاصيل الدورة
        $certificate = new Certificate();
        $certificate->user_id = $this->user->id;
        $certificate->course_name = $this->courseDetails['course_name'];
        $certificate->course_date = $this->courseDetails['course_date'];
        // قم بتخزين الشهادة في قاعدة البيانات أو توليدها كملف PDF أو أي عملية أخرى
        $certificate->save();
    }
}

