<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Models\Booking;
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
            $courses = Online::query()
                ->join('online_centers', 'onlines.id', '=', 'online_centers.id_online')
                ->where('onlines.id_course', $id_course)
                ->select(
                    'onlines.id',
                    'onlines.isopen',
                    'online_centers.*',
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
        if (Online_Center::query()->where("id", $id_online_center)->exists()
        ) {
            try {

                DB::beginTransaction();
                $count = Booking::where('id_online_center', $id_online_center)->count();
if($count<1) {
    Online_Center::where("id", $id_online_center)->delete();
    DB::commit();
    return MyApp::Json()->dataHandle("success", "data");}
else
{    DB::commit();
    return MyApp::Json()->dataHandle("تحتوي نسخة الدورة على مسجلين لا يمكن حذفها", "data");}

            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("data", "انت لا تملك برفايل لحذفه");//,$prof->getErrorMessage);


    }
  public function activateCopy    (Request $request,$id_online_center)
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
