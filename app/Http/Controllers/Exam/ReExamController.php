<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ReExam;
use App\MyApplication\MyApp;
use App\Services\ImageProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function League\Flysystem\delete;

class ReExamController extends Controller
{
    public function __construct( )
    {
        $this->middleware(["auth:sanctum"]);



    }
    public function index()
    {
        try {
            DB::beginTransaction();

            $result = DB::table('re_exams') // Specify the main table
            ->select(
                're_exams.id as id',
                'booking.id as id_book',
                'profiles.name as user_name',
                'profiles.lastName as user_lastname',
                'courses.name as course_name',
                'booking.count as count',
                DB::raw('DATE(online_centers.created_at) as created_at')
            )
                ->join('booking', function($join) {
                    $join->on('booking.id_user', '=', 're_exams.id_user')
                  ->on('booking.id_online_center', '=', 're_exams.id_online_center'); // Added semicolon
                })
                ->join('online_centers', 'online_centers.id', '=', 'booking.id_online_center')
                ->join('courses', 'courses.id', '=', 'online_centers.id_course')
                ->join('profiles', 'profiles.id_user', '=', auth()->id())
                ->get();

            DB::commit();
            return MyApp::Json()->dataHandle($result);
        }catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }



        return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }
    public function myindex()
    {
        try {
            DB::beginTransaction();

//            $result = DB::table('re_exams') // Specify the main table
//            ->select(
//                're_exams.id as id',
//                're_exams.status as status',
//                'booking.id as id_book',
//                'courses.name as course_name',
//                'booking.count as count',
//                DB::raw('DATE(online_centers.created_at) as created_at')
//            )
//                ->join('booking', function($join) {
//                    $join->on('booking.id_user', '=', 're_exams.id_user')
//                  ->on('booking.id_online_center', '=', 're_exams.id_online_center'); // Added semicolon
//                })
//                ->join('online_centers', 'online_centers.id', '=', 'booking.id_online_center')
//                ->join('courses', 'courses.id', '=', 'online_centers.id_course')
//                ->join('profiles', 'profiles.id_user', '=', 'booking.id_user')
//                ->get();


                        $result = DB::table('booking') // Specify the main table
            ->select(

                'booking.id as id_book',
                'courses.name as course_name',
                'booking.count as count',
                DB::raw('DATE(online_centers.created_at) as created_at_for_copy'),
                DB::raw('DATE(booking.updated_at) as updated_at_for_last_exam')
            )

                ->join('online_centers', 'online_centers.id', '=', 'booking.id_online_center')
                ->join('courses', 'courses.id', '=', 'online_centers.id_course')
                            ->where('booking.id_user',auth()->id())
                            ->where('booking.can', '0')
                            ->where('booking.done', '0')
                            ->where('booking.status', '1')
                            ->where('booking.count', '>=', '1')
                ->get();


            DB::commit();
            return MyApp::Json()->dataHandle($result);
        }catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }



        return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


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
    public function check(Request $request ,$id_reExam)
    {
        if (ReExam::query()->where("id", $id_reExam)->exists()) {
            try {
                DB::beginTransaction();

                $ad = ReExam::query()->where("id", $id_reExam)->first();
                if ($ad ) {
                    if ($request->status == '1') {
                        $book = Booking::query()->
                        where("id_online_center", $ad->id_online_center)->
                        where("id_user", $ad->id_user)
                            ->where('can', '0')
                            ->where('done', '0')
                            ->where('status', '1')
                            ->where('count', '>=', '1')
                            ->first(); // Use firstOrFail() if you want an exception on no results
                        $ad->delete();
                            $book->can = ($request->status);
                            $book->save();

                            DB::commit();
                            return MyApp::Json()->dataHandle("reExam successfully", "date");
                        }


                    else {
                        $book = Booking::query()->
                        where("id_online_center", $ad->id_online_center)->
                        where("id_user", $ad->id_user)
                            ->where('can', '0')
                            ->where('done', '0')
                            ->where('status', '1')
                            ->where('count', '>=', '1')
                            ->first();
                        if ($book != null) {
                            $ad->delete();

                            // Use firstOrFail() if you want an exception on no results

                            $book->can = ($request->status);
                            $book->save();
                            DB::commit();
                            return MyApp::Json()->dataHandle("unreExam successfully", "date");
                        } else
                            return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);

                    }
                }
                else {
                    return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);
                }
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        }




        else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }

}
