<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ReExam;
use App\MyApplication\MyApp;
use App\Services\ImageProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReExamController extends Controller
{
    public function __construct( )
    {
        $this->middleware(["auth:sanctum"]);



    }
    public function index()
    {
       // $responseData =
//            DB::table('re_exams')
//            ->select(
//                're_exams.*',
//                'booking.count',
//
//                'profiles.name as profile_name',
//                'profiles.lastName as profile_lastname',
//                'courses.name as course_name',
//                DB::raw('CASE WHEN online_centers.id_center IS NULL THEN "online" ELSE "center" END AS type'),
//                DB::raw('DATE(online_centers.created_at) as created_at') // Format created_at
//            )
//            ->join('booking', 'booking.id', '=', 're_exams.id_booking')
//            ->join('online_centers', 'online_centers.id', '=', 'booking.id_online_center')
//            ->join('courses', 'courses.id', '=', 'online_centers.id_course')
//            ->join('profiles', 'profiles.id_user', '=', 'booking.id_user')
//            ->where('re_exams.status', '=',0) // Adjust this condition as needed
//            ->get();
        $responseData = DB::table('re_exams')
            ->select(
                're_exams.*',
                'profiles.name as profile_name',
                'profiles.lastName as profile_lastname',
                'courses.name as course_name',
                DB::raw('CASE WHEN online_centers.id_center IS NULL THEN "online" ELSE "center" END AS type'),
                DB::raw('DATE(online_centers.created_at) as created_at')
            )
            ->join('booking', function($join) {
                $join->on('booking.id_user', '=', 're_exams.id_user')
                    ->where('booking.id_online_center', '=', 're_exams.id_online_center');
            })
            ->join('online_centers', 'online_centers.id', '=', 'booking.id_online_center')
            ->join('courses', 'courses.id', '=', 'online_centers.id_course')
            ->join('profiles', 'profiles.id_user', '=', 'booking.id_user')
            ->get();


        return response()->json($responseData);

    }
    public function create($id_online_center)
    {

        try {
            DB::beginTransaction();
            $book = Booking::query()
                ->where('id_online_center', $id_online_center)
                ->where('id_user', auth()->id())
                ->where('can', '0')
                ->where('done', '0')
                ->where('status', '1')
               ->where('count', '>=', '1')
                ->first(); // Use firstOrFail() if you want an exception on no results

            if($book!=null)
            {
                $data = ReExam::query()->
                where('id_online_center',$id_online_center)
                    ->where('id_user',auth()->id())
                     ->where('status','0')
                    ->first();
                if($data)
                    return response()->json([
                        "message" => "لقد قمت بتقديم طلب مسبقا  ",
                        "status" => "success",
                    ]);
                else{
                    $dateAdded = ReExam::create([
                    "status" =>'0',
                        'id_online_center'=>$id_online_center,
                    "id_user" => auth()->id()
                ]);
                    DB::commit();
                    return response()->json([
                        "message" => "تم تسجيل طلبك بنجاح  ",
                        "status" => "success",
                    ]);}
            }
            else{
    DB::commit();
    return response()->json([
        "message" => "لا يمكنك تقديم طلب اعادة امتحان  ",
        "status" => "success",
    ]);}



        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("date", "حدث خطا ما في الاضافة  لديك ");//,$prof->getErrorMessage);



    }
    public function check($id_reExam,$status)
    {
        if (ReExam::query()->where("id", $id_reExam)->exists()) {
            try {
                DB::beginTransaction();

                $ad = ReExam::where("id", $id_reExam)->first();
                if ($ad ) {
                    if($status=='1'){
                        $book = Booking::query()->
                        where("id_online_center", $ad->id_online_center)->
                        where("id_user", $ad->id_user)
                       ->where('can', '0')
                       ->where('done', '0')
                       ->where('status', '1')
                        ->where('count', '>=', '1')
                        ->first(); // Use firstOrFail() if you want an exception on no results

                        $ad->status = ($status);
                        $ad->save();
                        $book->can = ($status);
                        $ad->save();

                        DB::commit();
                        return MyApp::Json()->dataHandle("reExam successfully", "date");
                    }
                    else{
                        $ad = ReExam::query()->where("id", $id_reExam)->delete();
                        $book = Booking::query()->
                        where("id_online_center", $ad->id_online_center)->
                        where("id_user", $ad->id_user)
                            ->where('can', '0')
                            ->where('done', '0')
                            ->where('status', '1')
                            ->where('count', '>=', '1')
                            ->first(); // Use firstOrFail() if you want an exception on no results
                        $book->can = (status);
                        $ad->save();
                        DB::commit();
                        return MyApp::Json()->dataHandle("unreExam successfully", "date");
                    }}

            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }

}
