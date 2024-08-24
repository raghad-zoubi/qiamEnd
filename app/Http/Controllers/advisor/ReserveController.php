<?php


namespace App\Http\Controllers\advisor;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllCourses;
use App\Http\Resources\Present;
use App\Http\Resources\ShowDateUser;
use App\Http\Resources\ShowDay;
use App\Http\Resources\ShowDayUser;
use App\Models\Notification;
use App\Models\Rate;
use App\Models\Reserve;
use App\Models\Date;
use App\MyApplication\MyApp;
use App\MyApplication\Services\AdviserRuleValidation;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReserveController extends Controller
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
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
                        $user=$this->sendNotificationReserve($request->id, 1);

                        $ad->status = ($request->status);
                    $ad->save();
                        DB::commit();
                        return MyApp::Json()->dataHandle("reserved successfully", "date");
                }
                    if($request->status=='0'){
                        $user=$this->sendNotificationReserve($request->id, 0);

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
    public function sendNotificationReserve($id_reserve, $status)
    {


        try {
            DB::beginTransaction();


            $partbody = DB::table('reserves')->select(
                'dates.day as day',
                'users.fcm_token as fcm',
                'users.id as id_user',
                'advisers.name as name_advisers',
                DB::raw('DATE(reserves.created_at) as createdAtReserve')
            )
                ->join('dates', 'dates.id', '=', 'reserves.id_date')
                ->join('advisers', 'advisers.id', '=', 'dates.id_adviser')
                ->join('users', 'users.id', '=', 'reserves.id_user')
                ->where('reserves.id', $id_reserve)
                ->first();

            if ($partbody) {
                $deviceTokens = [
                    $partbody->fcm];

                if ($status == 1) {
                    $body =
                        ' لقد تمت الموافقةعلى موعدالاستشارة ' .// $partbody->day .
                        ' لدى ' . $partbody->name_advisers .
                        ' المقدم بتاريخ ' . $partbody->createdAtReserve;

                } else if ($status == 0) {
                    $body =
                        ' لم تتم الموافقة على موعدالاستشارة ' .// $partbody->day .
                        ' لدى ' . $partbody->name_advisers .
                        ' المقدم بتاريخ ' . $partbody->createdAtReserve;


                }

                $title = ' ';

                $data = ['key' => 'value'];

                $adviserAdded = Notification::create([
                    "title" => $title,
                    "body" => $body,
                    "id_user" => $partbody->id_user,

                ]);

                $status = $this->fcmService->sendNotification($deviceTokens, $title, $body, $data);

                DB::commit();

            } else {
                return response()->json([
                    'massege' => 'حدث خطا ما ']);
            }


        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


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
