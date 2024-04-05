<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\CoursePaper;
use App\Models\d4;
use App\Models\Online_Center;
use App\MyApplication\MyApp;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @property CoursesRuleValidation rules
 */
class CenterController extends Controller
{
    public function __construct()
    {
       // $this->middleware(["auth:sanctum"]);
        $this->rules = new CoursesRuleValidation();
    }
    public function index()
    {
        $center = Center::query()->get();
               return MyApp::Json()->dataHandle($center,"center");
    }

    public function show()
    {

    }

    public function create(Request $request)
    {
       //  $request->validate($this->rules->
//         onlyKey(["start","end","numberHours","price",
//         "numberLectures","id_course","id_form","id_poll"],true));
            try {
                DB::beginTransaction();
                $center =Center::create([
                    "start"=>$request->start,
                    "end"=>$request->end,
                    "numberHours"=>$request->numberHours,
                    "numberContents"=>$request->numberContents,
                    "id_course"=>$request->id_course,
                    "price"=>$request->price
                ]);
                $onlinecenter = Online_Center::create([
                    "id_center"=>$center->id,
                    "id_online"=>null,
                    "id_course" =>$request->id_course,
                ]);


                if($request->has('id_form'))
                    $onlinepaper = CoursePaper::create([
                        "id_online_center"=>$onlinecenter->id,
                        "id_paper"=>$request->id_form,

                    ]);
                if($request->has('id_poll'))
                    $onlinepaper = CoursePaper::create([
                        "id_online_center"=>$onlinecenter->id,
                        "id_paper"=>$request->id_poll,

                    ]);
                DB::commit();

                return MyApp::Json()->dataHandle($center,"center");
            }catch (\Exception $e){
                MyApp::uploadFile()->rollBackUpload();
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

            return MyApp::Json()->errorHandle("ceneter",$courceAdded->getErrorMessage());
        }




    public function update(Request $request): JsonResponse
    {
        $request->validate($this->rules->
        onlyKey(["start","end","numberHours","price",
            "numberLectures","id_course","id_form","id_poll"],true));
        $center = Center::where("id",$request->id)->first();
        try {
            DB::beginTransaction();
            $center->update([
                    "start"=>$request->start,
                    "end"=>$request->end,
                    "numberHours"=>$request->numberHours,
                    "numberLectures"=>$request->numberLectures,
                    "id_course"=>$request->id_course,
                    "id_form"=>$request->id_form,
                    "id_poll"=>$request->id_poll,
                    "price"=>$request->price
            ]);
            DB::commit();
            return MyApp::Json()->dataHandle("Successfully updated center course.","message");
        }catch (\Exception $e){
            MyApp::uploadFile()->rollBackUpload();
            DB::rollBack();
            throw new \Exception($e->getMessage(),$e->getCode());
        }

        return MyApp::Json()->errorHandle("center",$center->getErrorMessage());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Center::query()->where("id", $id)->exists()) {
            try {

                DB::beginTransaction();
                Center::where("id", $id)->delete();
                DB::commit();
                return MyApp::Json()->dataHandle("success", "center");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("center", "حدث خطا ما في الحذف ");//,$prof->getErrorMessage);


    }
}
