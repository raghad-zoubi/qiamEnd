<?php

namespace App\Http\Controllers\advisor;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexDateReserve;
use App\Http\Resources\ShowDay;
use App\Http\Resources\StatisticAdvisor;
use App\Http\Resources\VideoCourses;
use App\Models\Adviser;
use App\Models\Reserve;
use App\Models\Date;
use App\Models\User;
use App\MyApplication\MyApp;
use App\MyApplication\Services\AdviserRuleValidation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @property AdviserRuleValidation() rules
 */
class DateController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:sanctum","multi.auth:0|1"]);
//        $this->middleware(["auth:sanctum"]);

        $this->rules = new AdviserRuleValidation();
    }

    public function index($id,$type)
    {
        if (Adviser::query()->where("id", $id)->exists()) {

            try {

                DB::beginTransaction();
                if($type=='all')
                  //   وقيد الانتظار كلشي محجوز ومو محجوز
                    {    // dd($type);
                    $DateGet = Date::with('reserve')
                        ->where('id_adviser', '=', $id)
              ->where( 'day', '>', Carbon::now()->subDays(1))

                        ->get();
                }//time
              else  if($type=='no')
                //ليست محجوزه من قبل احد
                    {
                    $DateGet  = Date::doesntHave('reserve')
                        ->where('id_adviser', '=', $id)
                        ->where( 'day', '>', Carbon::now()->subDays(1))

                        ->get();
                }
              else   if($type=='done') {
                    // محجوزه وموافق عليها
                    $DateGet =  Date::whereHas('reserve', function($q){
                               $q->where('status','=','1');
                           }) ->where('id_adviser','=', $id)
                        ->where( 'day', '>', Carbon::now()->subDays(1))

                        ->get();
                }
              else      if($type=='wait') {
                    // محجوزة بس لسا مو موافق عليها
                    $DateGet =  Date::whereHas('reserve', function($q){
                        $q->where('status','=','0');
                    }) ->where('id_adviser','=', $id)
                        ->where( 'day', '>', Carbon::now()->subDays(1))

                        ->get();
                }
                DB::commit();
                //
                return response()->json([
                    '$DateGet' => IndexDateReserve::collection($DateGet),
                    //   '$DateGet' =>($DateGet),
                ]);
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("Date", "حدث حطا ما في العرض لديك");//,$prof->getErrorMessage);

    }

    public function create($id_adviser,Request $request)
    {
        //  $request->validate($this->rules->onlyKey(["time", "day", "id_adviser"], true));

//        $request->validate([
//            "id_adviser" => ["required", "array"],
//            "id_adviser.*" => ["numeric",],
//        ]);
        try {
            DB::beginTransaction();

            if (Adviser::query()->where("id", $id_adviser)->exists()) {
               if ($request->has('date') && $request->date != null) {

                    foreach ($request->date as $da) {

                             foreach ($da['times'] as $t) {
                            $dateAdded = Date::create([
                                "from" => ($t['from']),
                                "to" => ($t['to']),
                                "day" =>$da['day'],//$d,
                                "id_adviser" => $id_adviser
                            ]);
                        }
                    }
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

   public function showday($id_adviser,$d )
   {

       try {


           DB::beginTransaction();

           $DateGet = Date::where('id_adviser', $id_adviser)
               ->where('day', $d)
                       ->with('reserve')
               ->get();
            DB::commit();
            return response()->json([
                'DateGet' =>//$DateGet
                    ShowDay::collection($DateGet),
            ]);

        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("date", "حدث خطا ما في عرض  لديك ");//,$prof->getErrorMessage);

    }


    public function delete($id)
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
