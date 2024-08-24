<?php
//
//namespace App\Notifications;
//
//use App\Models\Admin;
//use App\Models\Customer;
//use App\Models\Order;
//use App\Models\Service;
//use App\Models\User;
//use App\Services\FCMService;
//use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Notifications\Messages\MailMessage;
//use Illuminate\Notifications\Notification;
//use Kutia\Larafirebase\Messages\FirebaseMessage;
//use Illuminate\Notifications\Channels\DatabaseChannel;
//class Accept extends Notification
//{
//    use Queueable;
//    private $fcmToken;
//    private $order;
//
//    public function __construct( $fcmToken,$order)
//    {
//        $this->fcmToken=$fcmToken;
//        $this->order=$order;
//
//    }
//
//    public function via()
//    {
//        return ['database'];
//    }
//    public function toFirebase()
//    {
////        $service = Service::where('id', $this->order->service_id)->get()->first();
////        $admin = Admin::where('id', $service->admin_id)->get()->first();
//        FCMService::send(
//            $this->fcmToken,
//            [
//
//                'id' => '1',//$this->order->id,
//                'title' => 'tt1',//'your request has been accepted by:',
//                'user' => 'uu',//$admin->name,
//
//            ]
//        );
//    }
//
//    public function toDatabase(){
//
////        $service=Service::where('id',$this->order->service_id)->get()->first();
////        $admin=Admin::where('id',$service->admin_id)->get()->first();
//         return   [
//                'id'=>1,//$this->order->id,
//                'title'=>'your request has been accepted by:',
//                'body'=>'bb',//$admin->name,
//
//            ];
//
// }
//}
