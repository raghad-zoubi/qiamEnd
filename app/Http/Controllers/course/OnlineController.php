<?php

namespace App\Http\Controllers;

use App\Models\Online;
use App\Models\Cours;
use App\MyApplication\MyApp;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @property CoursesRuleValidation rules
 */
class OnlineController extends Controller
{

    public function __construct()
    {
        $this->middleware(["auth:sanctum"]);
        $this->rules = new CoursesRuleValidation();
    }

    public function index()
    {
        $online = Online::query()->get();
        return MyApp::Json()->dataHandle($online, "online");
    }

    public function create(Request $request)
    {
        $request->validate($this->rules->onlyKey(["Exam", "amount", "serial",
            "durationExam", "id_course", "id_form", "id_poll"], true));
        try {
            DB::beginTransaction();
            $courceAdded = Online::create([
                "Exam" => $request->exam,
                "amount" => $request->amount,
                "serial" => $request->serial,
                "durationExam" => $request->durationExam,
                "id_course" => $request->id_course,
                "id_form" => $request->id_form,
                "id_poll" => $request->id_poll,
            ]);
            DB::commit();

            return MyApp::Json()->dataHandle($courceAdded, "cours");
        } catch (\Exception $e) {
            MyApp::uploadFile()->rollBackUpload();
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return MyApp::Json()->errorHandle("course", $file->getErrorMessage());
    }


    public function updlate(Request $request): JsonResponse
    {

        $request->validate($this->rules->onlyKey(["Exam", "amount", "serial",
            "durationExam", "id_course", "id_form", "id_poll"], true));
        $online = Online::where("id", $request->id)->first();
        try {
            DB::beginTransaction();
            $online->update([
                "Exam" => $request->exam,
                "amount" => $request->amount,
                "serial" => $request->serial,
                "durationExam" => $request->durationExam,
                "id_course" => $request->id_course,
                "id_form" => $request->id_form,
                "id_poll" => $request->id_poll,
            ]);
            DB::commit();
            return MyApp::Json()->dataHandle("Successfully updated online course.", "message");
        } catch (\Exception $e) {
            MyApp::uploadFile()->rollBackUpload();
            DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return MyApp::Json()->errorHandle("online", $online->getErrorMessage());
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate($this->rules->
        onlyKey(["Exam", "amount", "serial",
            "durationExam", "id_course", "id_form", "id_poll"],true));
        $online = Online::where("id",$request->id)->first();
        try {
            DB::beginTransaction();
            $online->update([
                "Exam"=>$request->exam,
                "durationExam"=>$request->durationExam,
                "id_course"=>$request->id_course,
                "id_form"=>$request->id_form,
                "id_poll"=>$request->id_poll,
                "amount"=>$request->amount
            ]);
            DB::commit();
            return MyApp::Json()->dataHandle("Successfully updated online course.","message");
        }catch (\Exception $e){
            MyApp::uploadFile()->rollBackUpload();
            DB::rollBack();
            throw new \Exception($e->getMessage(),$e->getCode());
        }

        return MyApp::Json()->errorHandle("online",$online->getErrorMessage());
    }

    public function destroy($id)
    {
        if (Online::query()->where("id", $id)->exists()) {
            try {

                DB::beginTransaction();
                Online::where("id", $id)->delete();
                DB::commit();
                return MyApp::Json()->dataHandle("success", "online");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("online", "حدث خطا ما في الحذف ");//,$prof->getErrorMessage);


    }
}
