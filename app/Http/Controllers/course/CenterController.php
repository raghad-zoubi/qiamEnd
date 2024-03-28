<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\d4;
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
        $this->middleware(["auth:sanctum"]);
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
         $request->validate($this->rules->
         onlyKey(["start","end","numberHours","price",
         "numberLectures","id_course","id_form","id_poll"],true));
            try {
                DB::beginTransaction();
                $courceAdded =Center::create([
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

                return MyApp::Json()->dataHandle($courceAdded,"course");
            }catch (\Exception $e){
                MyApp::uploadFile()->rollBackUpload();
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

            return MyApp::Json()->errorHandle("course",$courceAdded->getErrorMessage());
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
