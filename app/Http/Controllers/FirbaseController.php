<?php

namespace App\Http\Controllers;

use App\Models\Adviser;
use App\Models\Order;
use App\Models\User;
use App\Notifications\Accept;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use function Illuminate\Database\Eloquent\Factories\factoryForModel;
use Kreait\Firebase\Factory;
use App\Services\FirebaseService;
use Kreait\Laravel\Firebase\Facades\Firebase;

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


//
//    public function sendNotification(Request $request)
//    {
//        $request->validate([
//            'device_token' => 'required|string',
//            'title' => 'required|string',
//            'body' => 'required|string',
//        ]);
//
//        $deviceToken = $request->input('device_token');
//        $title = $request->input('title');
//        $body = $request->input('body');
//
//        try {
//            $this->firebaseService->sendNotification($deviceToken, $title, $body);
//            return response()->json(['message' => 'Notification sent successfully']);
//        } catch (\Exception $e) {
//            return response()->json(['error' => $e->getMessage()], 500);
//        }
    //}
  public function send()
    {        $order = Adviser::where('id', 3)->get()->first();
        $users = User::where('id', 1)->get()->first();

        $fcmToken=$users->fcm_token;
        // dd([$fcmToken]);
        Notification::send($users, new Accept([$fcmToken],$order));
        (new NotifactionController())->sendNotificationrToUser($fcmToken,3);

    }

}

