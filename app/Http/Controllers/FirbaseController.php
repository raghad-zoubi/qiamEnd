<?php

namespace App\Http\Controllers;

use App\Models\Adviser;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\Accept;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use function Illuminate\Database\Eloquent\Factories\factoryForModel;
use Kreait\Firebase\Factory;
use App\Services\FirebaseService;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Notifications\FirebasePushNotification;

class FirbaseController extends Controller
{


    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    public function __invoke()
    {
        //
    }


    public function reseveNotification()
    {
        // Correctly define the path to your service account JSON file
        $serviceAccountPath = __DIR__ . '/alqyam-1d894-firebase-adminsdk-jrajn-ba538d23d5.json';

        // Initialize Firebase with the service account and database URL
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccountPath)
            ->withDatabaseUri('https://alqyam-1d894-default-rtdb.firebaseio.com/');

        // Create the database instance
        $database = $firebase->createDatabase();

        // Get a reference to the 'data' node
        $reference = $database->getReference('data');

        // Get the value of the 'data' node
        $data = $reference->getValue();


  return response()->json($data);
    }


  public function send()
    {

    try {

        //  $order = Adviser::where('id', 3)->get()->first();
        $users = User::where('id', auth()->id())->get()->first();

        $fcmToken=//"esw0ZPloRSOW-V4LMdCpwM:APA91bHMZzaFJkZzEmISiM1BRO9nKXefuogHd-TNLIeXY3P9KLj9ZqRGVxb-B5q60oypsEvdTTvteTHc-7xPOCMVYFepz9Pg4WMJ0P8nF6IhofxwV8UhM70Q1dDqSaiLJoakkIEcHiGc";
        $users->fcm_token;
        // dd([$fcmToken]);
        Notification::send($users, new Accept($fcmToken));
        (new NotifactionController())->sendNotificationrToUser($fcmToken);
    }
catch (\Exception $e) {

        throw new \Exception($e->getMessage());
    }

//dd($fcmToken);
    }
////////


    public function sendNotification(Request $request)
    {



//            DB::beginTransaction();
//            $profileGet = Profile::where(
//                "id_user", auth()->id())->get();
$users = User::where('id', auth()->id())->get()->first();
//            $user = User::find($users);
//            $fcmToken = $users->fcm_token;
            dd($request->fcm);
        //    $user->pushNotification('auth()->user()->name' . 'send you massage', "message->body", "message");
        //dd('ssssss');


        Notification::send($users, new Accept([$fcmToken],$users));
        (new NotifactionController)->sendNotificationrToUser($fcmToken,1);
    }



}

