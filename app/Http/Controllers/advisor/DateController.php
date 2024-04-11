<?php

namespace App\Http\Controllers\advisor;

use App\Http\Controllers\Controller;
use App\Models\Adviser;
use App\Models\Reserve;
use App\Models\Date;
use App\Models\User;
use App\MyApplication\MyApp;
use App\MyApplication\Services\AdviserRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @property AdviserRuleValidation() rules
 */
class DateController extends Controller
{
    public function __construct()
    {
        //$this->middleware(["auth:sanctum"]);
        $this->rules = new AdviserRuleValidation();
    }

  public function index($id)
    {
        if (Adviser::query()->where("id", $id)->exists()) {

            try {

                DB::beginTransaction();
                $DateGet = Date::with('reserve')->
                where('id_adviser',$id)
                ->get();

                DB::commit();
                return MyApp::Json()->dataHandle($DateGet, "Date");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("Date","حدث حطا ما في العرض لديك");//,$prof->getErrorMessage);

    }


    public function create(Request $request)
    {
      //  $request->validate($this->rules->onlyKey(["time", "day", "id_adviser"], true));

//        $request->validate([
//            "id_adviser" => ["required", "array"],
//            "id_adviser.*" => ["numeric",],
//        ]);
   //   dd($request->data[0]);
        try {
            DB::beginTransaction();
            foreach ($request->data as $data) {
                if (Adviser::query()->where("id", $data['id_adviser'])->exists()) {
                    $dateAdded = Date::create([
                        "time" => ($data['time']),
                        "day" => ($data['day']),
                        "id_adviser" => $data['id_adviser']
                    ]);
                }
            }
            DB::commit();
            return MyApp::Json()->dataHandle(' Add successfully', "Date");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("date", "حدث خطا ما في الاضافة  لديك ");//,$prof->getErrorMessage);

    }

    public function show($status,$id_adviser)
    {
        //$status==محجوزه1
        //$status==0قيد الانتظار

        try {


            DB::beginTransaction();
            $dateGet = Reserve::with('date')->
            where("id_adviser",$id_adviser)->
            where("status",$status)
                ->get()->first();
//                $dateGet = Adviser::with('date')->
//                where("id",$id)->whereHas('reserve', function($q) use ($status) {
//                    $q-> where("status",$status);
//                })->get();
            //    where("status",$status)
            //     ->get()->first();

            DB::commit();
            return MyApp::Json()->dataHandle($dateGet, "date");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("date", "حدث خطا ما في عرض  لديك ");//,$prof->getErrorMessage);

    }

    public function update(Request $request)
    {
        $request->validate($this->rules->onlyKey(["time", "day", "id_adviser"], true));
        if (Date::query()->where("id", $request->id)->exists()) {
            try {
                DB::beginTransaction();

                $ad = Date::where("id", $request->id)->first();
                if ($ad) {
                    $ad->time = ($request->time);
                    $ad->day= ($request->day);
                    $ad->id_adviser= ($request->id_adviser);

                    $ad->save();
                }
                DB::commit();
                return MyApp::Json()->dataHandle("edit successfully", "date");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما في تعديل  لديك ");//,$prof->getErrorMessage);


    }

    public function destroy($id)
    {
        if (Date::query()->where("id", $id)->exists()) {
            try {

                DB::beginTransaction();
                Date::where("id", $id)->delete();
                DB::commit();
                return MyApp::Json()->dataHandle("success deleted", "date");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


    }
}
