<?php

namespace App\Http\Controllers;


namespace App\Http\Controllers;

use App\Models\Adviser;
use App\Models\Notification;
use App\MyApplication\MyApp;
use App\Services\FCMService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class NotifactionController extends Controller
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    public function sendNotificationBooking($id_book, $status)
    {


        try {
            DB::beginTransaction();


            $partbody = DB::table('booking')->select(
                'courses.name as course_name',
                'users.fcm_token as fcm',
                'profiles.id_user as id_user',
                'profiles.name as name',
                DB::raw('DATE(booking.created_at) as createdAtbook'),
                DB::raw('DATE(online_centers.created_at) as createdAcourse')
            )
                ->join('online_centers', 'online_centers.id', '=', 'booking.id_online_center')
                ->join('courses', 'courses.id', '=', 'online_centers.id_course')
                ->join('profiles', 'profiles.id_user', '=', 'booking.id_user')
                ->join('users', 'users.id', '=', 'booking.id_user')
                ->where('booking.id', $id_book)
                ->first();


            if ($partbody) {
                $deviceTokens = [
                    $partbody->fcm];
                if ($status == 2) {
                    $body =
                        ' لقد قمت بطلب حجز لدورة ' . $partbody->course_name .
                        ' نسخة ' . $partbody->createdAcourse .
                        ' بتاريخ ' . $partbody->createdAtbook;

                    $title = 'تذكير';
                } else if ($status == 1) {
                    $body =
                        ' لقد تمت الموافقة على حجزك لدورة ' . $partbody->course_name .
                        ' نسخة ' . $partbody->createdAcourse .
                        'المقدم بتاريخ ' . $partbody->createdAtbook;

                    $title = ' ';
                } else if ($status == 0) {
                    $body =
                        ' لقد تم رفض  حجزك لدورة ' . $partbody->course_name .
                        ' نسخة ' . $partbody->createdAcourse .
                        'المقدم بتاريخ ' . $partbody->createdAtbook;


                    $title = ' ';
                }

                $data = ['key' => 'value'];

                $adviserAdded = Notification::create([
                    "title" => $title,
                    "body" => $body,
                    "id_user" => $partbody->id_user,

                ]);

                $status = $this->fcmService->sendNotification($deviceTokens, $title, $body, $data);

                DB::commit();

            } else {
                return response()->json([
                    'massege' => 'حدث خطا ما ']);
            }


        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


    }
    public function sendNotificationReserve($id_reserve, $status)
    {


        try {
            DB::beginTransaction();


            $partbody = DB::table('reserves')->select(
                'dates.day as day',
                'users.fcm_token as fcm',
                'users.id as id_user',
                'advisers.name as name_advisers',
                DB::raw('DATE(reserves.created_at) as createdAtReserve')
            )
                ->join('dates', 'dates.id', '=', 'reserves.id_date')
                ->join('advisers', 'advisers.id', '=', 'dates.id_adviser')
                ->join('users', 'users.id', '=', 'reserves.id_user')
                ->where('reserves.id', $id_reserve)
                ->first();

            if ($partbody) {
                $deviceTokens = [
                    $partbody->fcm];

                if ($status == 1) {
                    $body =
                        ' لقد تمت الموافقةعلى موعدالاستشارة ' .// $partbody->day .
                        ' لدى ' . $partbody->name_advisers .
                        ' المقدم بتاريخ ' . $partbody->createdAtReserve;

                } else if ($status == 0) {
                    $body =
                        ' لم تتم الموافقة على موعدالاستشارة ' .// $partbody->day .
                        ' لدى ' . $partbody->name_advisers .
                        ' المقدم بتاريخ ' . $partbody->createdAtReserve;


                }

                $title = ' ';

                $data = ['key' => 'value'];

                $adviserAdded = Notification::create([
                    "title" => $title,
                    "body" => $body,
                    "id_user" => $partbody->id_user,

                ]);

                $status = $this->fcmService->sendNotification($deviceTokens, $title, $body, $data);

                DB::commit();

            } else {
                return response()->json([
                    'massege' => 'حدث خطا ما ']);
            }


        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


    }
    public function sendNotificationReExam($id_reExam, $status)
    {


        try {
            DB::beginTransaction();


            $partbody = DB::table('re_exams')->select(
                'courses.name as course_name',
                'users.fcm_token as fcm',
                'users.id as id_user',
                DB::raw('DATE(re_exams.created_at) as createdAtReExam')
            )
                ->join('online_centers', 'online_centers.id', '=', 're_exams.id_online_center')
                ->join('courses', 'courses.id', '=', 'online_centers.id_course')
                ->join('users', 'users.id', '=', 're_exams.id_user')
                ->where('re_exams.id', $id_reExam)
                ->first();


            if ($partbody) {
                $deviceTokens = [
                    $partbody->fcm];

                if ($status == 1) {
                    $body =
                        ' لقد تمت الموافقةعلى طلب إعادة الامتحان ' .// $partbody->day .
                        ' لدورة ' . $partbody->course_name .
                        ' المقدم بتاريخ ' . $partbody->createdAtReExam;

                } else if ($status == 0) {
                    $body =
                        ' لم تتم الموافقة على  طلب إعادة الامتحان ' .// $partbody->day .
                        ' لدورة ' . $partbody->course_name .
                        ' المقدم بتاريخ ' . $partbody->createdAtReExam;


                }

                $title = ' ';

                $data = ['key' => 'value'];

                $adviserAdded = Notification::create([
                    "title" => $title,
                    "body" => $body,
                    "id_user" => $partbody->id_user,

                ]);

                $status = $this->fcmService->sendNotification($deviceTokens, $title, $body, $data);

                DB::commit();

            } else {
                return response()->json([
                    'massege' => 'حدث خطا ما ']);
            }


        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


    }
    public function sendNotificationAddMark($id_book)
    {


        try {
            DB::beginTransaction();



            $partbody = DB::table('booking')->select(
                'courses.name as course_name',
                'users.fcm_token as fcm',
                'profiles.id_user as id_user',
                'profiles.name as name',
                DB::raw('DATE(booking.created_at) as createdAtbook'),
                DB::raw('DATE(online_centers.created_at) as createdAcourse')
            )
                ->join('online_centers', 'online_centers.id', '=', 'booking.id_online_center')
                ->join('courses', 'courses.id', '=', 'online_centers.id_course')
                ->join('profiles', 'profiles.id_user', '=', 'booking.id_user')
                ->join('users', 'users.id', '=', 'booking.id_user')
                ->where('booking.id', $id_book)
                ->first();


            if ($partbody) {
                $deviceTokens = [
                    $partbody->fcm];


                    $body =
                        ' لقد حصلت على شهادة '. //.$partbody->name .'تهانينا'.
                        ' لدورة ' . $partbody->course_name .
                        '  نسخة ' . $partbody->createdAcourse.
                       'ضمن المركز ';


                $title = ' ';

                $data = ['key' => 'value'];

                $adviserAdded = Notification::create([
                    "title" => $title,
                    "body" => $body,
                    "id_user" => $partbody->id_user,

                ]);

                $status = $this->fcmService->sendNotification($deviceTokens, $title, $body, $data);

                DB::commit();

            } else {
                return response()->json([
                    'massege' => 'حدث خطا ما ']);
            }


        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


    }


    public function fcmToken(Request $request)
    {

        $user = User::find(auth()->id());
        $user->update(['fcm_token' => $request['fcm_token']]);

        return response()->json('fcm updated successfully', 200);

    }

    public function listNotifications()
    {$notifications = Notification::where('id_user', auth()->id())->
    ->where('created_at', '>=', now()->subMonth()) ->// Corrected method to get the date one month ago
    ->orderBy('created_at', 'DESC')->
    ->get();
        return $notifications;
    }
}
