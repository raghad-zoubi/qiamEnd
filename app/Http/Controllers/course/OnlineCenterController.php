<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Online;
use App\Models\Online_Center;
use App\MyApplication\MyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnlineCenterController extends Controller
{


    public function displayCopy($id_course)
    {
        try {
            $courses = Online::query()->where('id_course', $id_course)->get(['id','isopen']);

//            $courses = Online_Center::query()
//                ->whereHas('course', function ($query) use ($id_course) {
//                    $query->where('id_course', $id_course);
//                })
//                ->join('rates', 'online_centers.id', '=', 'rates.id_online_center')
//                ->where('online_centers.id_course', $id_course)
//                ->select(
//                    'online_centers.id',
//                    'online_centers.id_course', // Replace column1 with the actual column names
//                    'online_centers.id_center', // Replace column1 with the actual column names
//                 //   'online_centers.column2', // Replace column2 with the actual column names
//                    // Include all other non-aggregated columns from online_centers
//                    DB::raw('avg(rates.value) as average_rate'),
//                    DB::raw('count(booking.id) as total_bookings')
//                )
//                ->leftJoin('booking', 'online_centers.id', '=', 'booking.id_online_center')
//                ->where('booking.status', 1)
//                ->groupBy(
//                    'online_centers.id',
//                    'online_centers.id_course', // Replace column1 with the actual column names
//                    'online_centers.id_center', // Replace column1 with the actual column names
//                // Include all other non-aggregated columns from online_centers
//                )
//                ->get();

            $courses = Online::query()
                ->join('online_centers', 'onlines.id', '=', 'online_centers.id_online')
                ->where('onlines.id_course', $id_course)
                ->select(
                    'onlines.id',
                    'onlines.isopen',
                    'online_centers.*', // Select all columns from online_centers
                    DB::raw('IFNULL((SELECT avg(value) FROM rates WHERE rates.id_online_center = onlines.id), 0) as average_rate'),
                    DB::raw('(SELECT count(booking.id) FROM booking WHERE booking.id_online_center = onlines.id AND booking.status = 1) as total_bookings')
                )
                ->get();


            return MyApp::Json()->dataHandle($courses, "data");

    }
    catch (\Exception $e) {

        DB::rollBack();
        throw new \Exception($e->getMessage());
    }


        return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);

    }
    public function deleteCopy($id_online_center)
    {
       // try {




//        return MyApp::Json()->dataHandle($courses, "data");
//
//    }
//    catch (\Exception $e) {
//
//        DB::rollBack();
//        throw new \Exception($e->getMessage());
//    }


        return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Online_Center $online_Center)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Online_Center $online_Center)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Online_Center $online_Center)
    {
        //
    }
}
