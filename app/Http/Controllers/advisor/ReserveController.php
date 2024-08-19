<?php


namespace App\Http\Controllers\advisor;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllCourses;
use App\Http\Resources\Present;
use App\Http\Resources\ShowDateUser;
use App\Http\Resources\ShowDay;
use App\Http\Resources\ShowDayUser;
use App\Models\Rate;
use App\Models\Reserve;
use App\Models\Date;
use App\MyApplication\MyApp;
use App\MyApplication\Services\AdviserRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReserveController extends Controller
{
    public function __construct()
    {

        //     $this->middleware(["auth:sanctum","multi.auth:2"])->only(['display','create','present']);
       // $this->middleware(["auth:sanctum","multi.auth:0|1"])->only(['index','check','show']);
        $this->middleware(["auth:sanctum"]);

        $this->rules = new AdviserRuleValidation();
    }
    public function index($id)
    {

        try {

            DB::beginTransaction();

            $dateGet =
            $dates = Date::doesntHave('reserve')
                ->where('id_adviser', '=', $id)
                ->get();

            DB::commit();
            return MyApp::Json()->dataHandle($dateGet, "date");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("date", "حدث خطا ما في عرض  لديك ");//,$prof->getErrorMessage);

    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $dateAdded = Reserve::where([
                'id_date' => $request->id_date,
            ])->first();

            if (!is_null($dateAdded)) {
                return response()->json([
                    "message" => "لقد تم حجز هذا الموعد لا يمكنك حجزه",
                    "status" => "success",
                ]);
            }else{

            $dateAdded = Reserve::create([
                "status" =>'0',
                "id_date" => ($request->id_date),
                "id_user" => auth()->id()
            ]);}
            DB::commit();
            return MyApp::Json()->dataHandle("success", "Date");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("date", "حدث خطا ما في الاضافة  لديك ");//,$prof->getErrorMessage);



    }
    public function check(Request $request)
    {
        $request->validate($this->rules->onlyKey(["id","status"], true));
        if (Reserve::query()->where("id", $request->id)->exists()) {
            try {
                DB::beginTransaction();

                $ad = Reserve::where("id", $request->id)->first();
                if ($ad ) {
                    if($request->status=='1'){
                    $ad->status = ($request->status);
                    $ad->save();
                        DB::commit();
                        return MyApp::Json()->dataHandle("reserved successfully", "date");
                }
                else{
                    $ad = Reserve::query()->where("id", $request->id)->delete();
                    DB::commit();
                    return MyApp::Json()->dataHandle("unreserved successfully", "date");
                }}

            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }
    public function show()
    {

        try {

            DB::beginTransaction();
            $dateGet = Reserve::with('reserve')->
            where("id_user",auth()->id())->get();
            DB::commit();
            return MyApp::Json()->dataHandle($dateGet, "date");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("date", "حدث خطا ما في عرض  لديك ");//,$prof->getErrorMessage);

    }
    public function present($type)
    {

        try {

            DB::beginTransaction();
            if($type=='com') {
                $dateGet = Reserve::with('reserve2')->
                where("status", '1')->
                where("id_user", auth()->id())->

                orderBy(
                    "updated_at", 'asc')->
                get();
                // get(["status","reserve"]);
                DB::commit();
                return response()->json([
                    'data' => Present::collection($dateGet),
                ]);
            }
            else   if($type=='uncom') {
                $dateGet = Reserve::with('reserve2')->
                where("id_user", auth()->id())->

                where("status", '0')->
                orderBy(
                    "updated_at", 'asc')->
                get();
                // get(["status","reserve"]);
                DB::commit();
                // return MyApp::Json()->dataHandle($dateGet, "date");
                return response()->json([
                    'data' => Present::collection($dateGet),
                ]);
            }
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("date", "حدث خطا ما في عرض  لديك ");//,$prof->getErrorMessage);

    }
    public function displayDay($id_adviser )
    {

        try {

            DB::beginTransaction();

            $DateGet = Date::where('id_adviser', $id_adviser)
                ->with('reserve')
                ->get();
            DB::commit();

            // Filter and transform the data
            $filteredData = $DateGet->filter(function ($item) {
                return empty($item->reserve[0]);
            })->map(function ($item) {
                return [
                    'id_data' => $item->id,
                    'id_adviser' => $item->id_adviser,
                    'day' => $item->day,
                ];
            })->values();

            return response()->json([
                'DateGet' => $filteredData
                    //$filteredData
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return MyApp::Json()->errorHandle("date", "حدث خطا ما في عرض  لديك ");
    }
    public function displayDate($id_adviser, $day)
    {
        try {
            DB::beginTransaction();

            $DateGet = Date::where('id_adviser', $id_adviser)
                ->where('day', $day)
                ->with('reserve')
                ->get();

            DB::commit();

            // Filter and transform the data
            $filteredData = $DateGet->filter(function ($item) {
                return empty($item->reserve[0]);
            })->map(function ($item) {
                return [
                    'id_data' => $item->id,
                    'id_adviser' => $item->id_adviser,
                    'from' => $item->from,
                    'to' => $item->to,
                    'day' => $item->day,
                ];
            })->values();

            return response()->json([
                'DateGet' => $filteredData
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return MyApp::Json()->errorHandle("date", "حدث خطا ما في عرض  لديك ");
    }

}
