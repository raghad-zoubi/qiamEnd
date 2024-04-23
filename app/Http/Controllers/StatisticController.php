<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\d4;
use App\Models\User;
use App\MyApplication\MyApp;
use App\MyApplication\Services\PollFormRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @property PollFormRuleValidation rules
 */
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
            return MyApp::Json()->dataHandle($userCount, "form");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }



        return MyApp::Json()->errorHandle("userCount", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

    public function countUsersWithNullCenterId()
    {


        try {

            DB::beginTransaction();

            $userCount = Booking::whereDoesntHave('mark', function ($query) {
                $query->whereNotNull('id_center');
            })->distinct()->count('id_user');
            DB::commit();
            return MyApp::Json()->dataHandle($userCount, "form");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }



        return MyApp::Json()->errorHandle("userCount", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);


    }
    public function countUsersWithNullOnlineId()
    {


        try {

            DB::beginTransaction();

            $userCount = Booking::whereDoesntHave('mark', function ($query) {
                $query->whereNotNull('id_online');
            })->distinct()->count('id_user');
            DB::commit();
            return MyApp::Json()->dataHandle($userCount, "form");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }



        return MyApp::Json()->errorHandle("userCount", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);


    }

    public function index()
    {

        try {

            DB::beginTransaction();
            $formGet = d4::query()->get();
            DB::commit();
            return MyApp::Json()->dataHandle($formGet, "form");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }



        return MyApp::Json()->errorHandle("form", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }


}
