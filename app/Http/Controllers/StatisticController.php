<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatisticAdvisor;
use App\Models\Booking;
use App\Models\d4;
use App\Models\Date;
use App\Models\Reserve;
use App\Models\User;
use App\MyApplication\MyApp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StatisticController extends Controller
{
    public function __construct()
    {
        // $this->middleware(["auth:sanctum"]);
    }

    public function countvisitors()
    {

        try {

            DB::beginTransaction();
            $userCount = User::where('role', 'user')->count();
            DB::commit();
            return MyApp::Json()->dataHandle($userCount, "data");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

    public function useronline()
    {


        try {

            DB::beginTransaction();

            $userCount = Booking::whereHas('booking', function ($query) {
                $query->whereNotNull('id_online');
            })->distinct()->count('id_user');
            //->count('id_user');
            //->distinct()->distinct()->count('id_user');
            //->count('id_user');
            DB::commit();
            return MyApp::Json()->dataHandle($userCount, "data");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);


    }

    public function usercenter()
    {


        try {

            DB::beginTransaction();

            $userCount = Booking::whereHas('booking', function ($query) {
                $query->whereNotNull('id_center');
            })
                ->distinct()->count('id_user');
            //->count('id_user');
            //->distinct()->distinct()->count('id_user');
            //->count('id_user');
            DB::commit();
            return MyApp::Json()->dataHandle($userCount, "data");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);


    }

    public function havecertificate()
    {


        try {

            DB::beginTransaction();
//المسجلين
            $Count = Booking::whereHas('booking', function ($query) {
                $query->whereHas('online', function ($subQuery) {
                    $subQuery->whereNotNull('id_online')
                        ->where('exam', '=', '1');
                });
            })->distinct()->count('id_user');
            //->count('id_user');
//اللي اخدين شهاده
            $userCount = Booking::whereHas('booking', function ($query) {
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
            DB::commit();
            return response()->json([
                'withcertificate' => $userCount,
                'withoutcertificate' => $Count,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);


    }

    public function completedcourse()
    {


        try {

            DB::beginTransaction();

            $Count = Booking::whereHas('booking', function ($query) {
                $query->whereNotNull('id_online');
            })
                ->where('done', '=', '0') ->distinct()->count('id_user');
            $userCount = Booking::whereHas('booking', function ($query) {
                $query->whereNotNull('id_online');
            })->where('done', '=', '1')->distinct()->count('id_user');

            DB::commit();
            return response()->json([
                'completedcourse' => $userCount,
                'uncompletedcourse' => $Count,
            ]);        } catch (\Exception $e) {

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


    }}
