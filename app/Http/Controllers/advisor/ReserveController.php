<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use App\Models\Date;
use App\MyApplication\MyApp;
use App\MyApplication\Services\AdviserRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReserveController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:sanctum"]);
        $this->rules = new AdviserRuleValidation();
    }
    public function index($id_adviser)
    {

        try {

            DB::beginTransaction();
            $dateGet = Reserve::with('date')->
            where("id_user",auth()->id())->
            where("id_adviser",$id_adviser)
                ->get()->first();
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
            $dateAdded = Reserve::create([
                "status" =>'0',
                "id_date" => ($request->id_date),
                "id_adviser" => $request->id_adviser,
                "id_user" => auth()->id()
            ]);
            DB::commit();
            return MyApp::Json()->dataHandle($dateAdded, "Date");
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
//                DB::commit();
//                return MyApp::Json()->dataHandle("reserved successfully", "date");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }

    public function show($status)
    {

        try {

            DB::beginTransaction();
            $dateGet = Reserve::with('date')->
            where("id_user",auth()->id())->
            where("status",$status)
                ->get()->first();
            DB::commit();
            return MyApp::Json()->dataHandle($dateGet, "date");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("date", "حدث خطا ما في عرض  لديك ");//,$prof->getErrorMessage);

    }



    public function destroy(Reserve $adv_Dat_Use)
    {

    }
}
