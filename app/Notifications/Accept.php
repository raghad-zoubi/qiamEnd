<?php

namespace App\Notifications;

use App\Models\Admin;
use App\Models\Adviser;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kutia\Larafirebase\Messages\FirebaseMessage;
use Illuminate\Notifications\Channels\DatabaseChannel;
class Accept extends Notification
{
    use Queueable;
    private $fcmToken;
    private $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $fcmToken,$order)
    {
        $this->fcmToken=$fcmToken;
        $this->order=$order;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
    public function toFirebase($notifiable)
    {
//        $service = Adviser::where('id', $this->order->service_id)->get()->first();
//        $admin = Admin::where('id', $service->admin_id)->get()->first();

        FCMService::send(
            $this->fcmToken,
            [

                'id' => 'this->order->id',
                'title' => 'your request has been accepted by:',
                'user' => 'admin->name',

            ]
        );
    }
      //  dd('hhh');


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable){

//        $service=Service::where('id',$this->order->service_id)->get()->first();
//        $admin=Admin::where('id',$service->admin_id)->get()->first();
//
         return   [
                'id'=>'this->order->id',
                'title'=>'your request has been accepted by:',
                'body'=>'admin->name',

            ];

 }
}
