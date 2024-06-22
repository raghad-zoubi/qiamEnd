<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommonCourses;
use App\Http\Resources\DetailsCenterCopy;
use App\Http\Resources\DetailsOnlineCopy;
use App\Models\Booking;
use App\Models\Center;
use App\Models\Course;
use App\Models\Online;
use App\Models\Online_Center;
use App\Models\Profile;
use App\MyApplication\MyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnlineCenterController extends Controller
{


    public function displayCopy($id_course)
    {
        try {
            $onlineCourses = Online::query()
                ->join('online_centers', 'onlines.id', '=', 'online_centers.id_online')
                ->where('onlines.id_course', $id_course)
                ->select(
                    'onlines.id as course_id',
                    DB::raw('NULL as start'),
                    DB::raw('NULL as end'),
                    'online_centers.id as center_id',
                    'onlines.isopen',
                    'online_centers.*',
                    DB::raw('IFNULL((SELECT avg(value) FROM rates WHERE rates.id_online_center = online_centers.id), 0) as average_rate'),
                    DB::raw('(SELECT COUNT(booking.id) FROM booking WHERE booking.id_online_center = online_centers.id AND booking.status = "1") as total_bookings')
                );

            $centerCourses = Center::query()
                ->join('online_centers', 'centers.id', '=', 'online_centers.id_center')
                ->where('centers.id_course', $id_course)
                ->select(
                    'centers.id as course_id',
                    'centers.start',
                    'centers.end',
                    'online_centers.id as center_id',
                    DB::raw('NULL as isopen'),
                    'online_centers.*',
                    DB::raw('IFNULL((SELECT avg(value) FROM rates WHERE rates.id_online_center = online_centers.id), 0) as average_rate'),
                    DB::raw('(SELECT COUNT(booking.id) FROM booking WHERE booking.id_online_center = online_centers.id AND booking.status = "1") as total_bookings')
                );

            $courses = $onlineCourses->union($centerCourses)->get();

            return MyApp::Json()->dataHandle($courses, "data");


        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);

    }
    public function detailsOnlineCopy($id_online_center)
    {
        try {

            $onlineData = Online_Center::with([
                'online',
               'content2',
               'coursepaper.paper',
            ])->where('id',$id_online_center)->get();

            return response()->json([
               'data' => DetailsOnlineCopy::collection($onlineData),
            ]);


        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);



                }
    public function detailsCenterCopy($id_online_center)
    {try{
        $onlineData = Online_Center::with([
            'center',
        ])->where('id',$id_online_center)->get();

        return response()->json([
         'data' => DetailsCenterCopy::collection($onlineData),
            //      'data' => ($onlineData),
        ]);


    } catch (\Exception $e) {

DB::rollBack();
throw new \Exception($e->getMessage());
}


return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


}
    public function deleteCopy($id_online_center)
    {
        if (Online_Center::query()->where("id", $id_online_center)->exists()
        ) {
            try {

                DB::beginTransaction();
                $count = Booking::where('id_online_center', $id_online_center)->count();
                if ($count < 1) {
                    Online_Center::where("id", $id_online_center)->delete();
                    DB::commit();
                    return MyApp::Json()->dataHandle("success", "data");
                } else {
                    DB::commit();
                    return MyApp::Json()->dataHandle("تحتوي نسخة الدورة على مسجلين لا يمكن حذفها", "data");
                }

            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("data", "انت لا تملك برفايل لحذفه");//,$prof->getErrorMessage);


    }

    public function activateCopy(Request $request, $id_online_center)
    {
        if (Online_Center::query()->where("id", $id_online_center)->exists()
        ) {
            try {
                DB::beginTransaction();

                $ad = Online::whereHas('onlineCenters', function ($query) use ($id_online_center) {
                    $query->where('id', $id_online_center);
                })->get();

                if ($ad->isNotEmpty()) {
                    foreach ($ad as $item) {
                        $item->isopen = $request->isopen;
                        $item->save();
                    }
                    DB::commit();
                    return MyApp::Json()->dataHandle($ad, "date");
                }

            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("data", "انت لا تملك برفايل لحذفه");//,$prof->getErrorMessage);


    }


}
