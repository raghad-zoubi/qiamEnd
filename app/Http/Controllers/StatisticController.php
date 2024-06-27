<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatisticAdvisor;
use App\Http\Resources\StatisticData;
use App\Models\Booking;
use App\Models\d4;
use App\Models\Date;
use App\Models\Online;
use App\Models\Reserve;
use App\Models\User;
use App\Models\UserCertificate;
use App\MyApplication\MyApp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class StatisticController extends Controller
{
    public function __construct()
    {
        // $this->middleware(["auth:sanctum"]);
    }

    public function count()
    {

        try {

            DB::beginTransaction();
            $visitorCount = User::where('role', 'user')->count();
            $onlineCount = Booking::whereHas('booking', function ($query) {
                $query->whereNotNull('id_online');
            })->distinct()->count('id_user');
            $centerCount = Booking::whereHas('booking', function ($query) {
                $query->whereNotNull('id_center');
            })
                ->distinct()->count('id_user');
            DB::commit();
            return response()->json([
                'visitorCount' => $visitorCount,
                'onlineCount' => $onlineCount,
                'centerCount' => $centerCount,
            ]);        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

//Statistic
    public function proportion()
    {


        try {

            DB::beginTransaction();
//المسجلين
            $withoutcertificate = Booking::whereHas('booking', function ($query) {
                $query->whereHas('online', function ($subQuery) {
                    $subQuery->whereNotNull('id_online')
                        ->where('exam', '=', '1');
                });
            })->distinct()->count('id_user');
            //->count('id_user');
//اللي اخدين شهاده
            $withcertificate = Booking::whereHas('booking', function ($query) {
                $query->whereHas('online', function ($subQuery) {
                    $subQuery->whereNotNull('id_online')
                        ->where('exam', '=', '1');
                });
            })->whereExists(function ($query) {
                $query->select('id')
                    ->from('user_certificate')
                    ->whereColumn('user_certificate.id_booking', 'booking.id');
            })
                ->distinct()->count('id_user');
            $uncompletedcourse= Booking::whereHas('booking', function ($query) {
                $query->whereNotNull('id_online');
            })
                ->where('done', '=', '0') ->distinct()->count('id_user');
            $completedcourse = Booking::whereHas('booking', function ($query) {
                $query->whereNotNull('id_online');
            })->where('done', '=', '1')->distinct()->count('id_user');


            DB::commit();
            return response()->json([


                'data1' => [
                    ['name' => 'نسبة حاصلون على الشهادة', 'value' => $withcertificate],
                    ['name' => 'نسبة المسجلين', 'value' => $withoutcertificate]
                ]
                ,

                'data2' => [
                    ['name' => 'نسبة اتمام الطلاب للدورات', 'value' => $completedcourse],
                    ['name' => 'نسبة المسجلين', 'value' => $uncompletedcourse]
                ]


            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);


    }

    public function statistic($year)
    {


        try {

            DB::beginTransaction();


                $bookingsByMonth = Booking::selectRaw('YEAR(created_at) as year, MONTHNAME(created_at) as month, COUNT(*) as bookings')
                    ->whereYear('created_at', $year)
                    ->groupBy('year', 'month')
                    ->get();


                $certificatesByMonth = UserCertificate::selectRaw('YEAR(created_at) as year, MONTHNAME(created_at) as month, COUNT(*) as certificates')
                    ->whereYear('created_at', $year)
                    ->groupBy('year', 'month')
                    ->get();



            DB::commit();
            return response()->json([
                'bookingsByMonth' => $bookingsByMonth,
                'certificatesByMonth' => $certificatesByMonth,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);


    }


    public function advisernow()
    {


        try {

            DB::beginTransaction();

//اللي اخدين شهاده
            $date = Carbon::now();
            $d=$date->format("Y-m-d");
            //     dd($d);
            $reservations = Reserve::whereHas('reserve', function ($query) use ($d) {
                $query->where('day', $d);
            })

                ->with(['users2', 'reserve2'])
                ->get();

            DB::commit();
            //return MyApp::Json()->dataHandle($reservations, "data");
            return response()->json([
                'data' => StatisticAdvisor::collection($reservations),
            ]);
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return MyApp::Json()->errorHandle("data", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);


    }



}
