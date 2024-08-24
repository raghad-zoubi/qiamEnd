<?php
//
//
//namespace App\Http\Controllers;
//
//
//
//        use Illuminate\Http\Request;
//        use App\Services\FirebaseNotificationService;
//
//        class NotificationController extends Controller
//        {
//            protected $firebaseNotificationService;
//
//            public function __construct(FirebaseNotificationService $firebaseNotificationService)
//            {
//                $this->firebaseNotificationService = $firebaseNotificationService;
//            }
//
//            public function send(Request $request)
//            {
//                // Validate incoming request
////                $request->validate([
////                    'device_token' => 'elHIkEHIRnK5_x_y9m-PQ9:APA91bGbvLkZwt4lUF15L-VSe-KnNO6n-vcpm19fszsXtK1D5jzU07hphiJ5cNJZ2ztix0byCQZp_q33VFLOfxkY-jZt1_Y7a2lGCmuQGXOqtYyHg3Tj3czU8vwW_moFnDfaVazN-ZO1',
////                    'title' => 'required|string',
////                    'body' => 'required|string',
////                    'data' => 'array', // optional, additional data
////                ]);
//
//                // Retrieve data from request
//                $deviceToken = $request->input('device_token');
//                $title = $request->input('title');
//                $body = $request->input('body');
//                $data = $request->input('data', []);
//
//                // Send notification
//                $this->firebaseNotificationService->sendNotification($deviceToken, $title, $body, $data);
//
//                return response()->json(['success' => true, 'message' => 'Notification sent successfully!']);
//            }
//        }
