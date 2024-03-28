<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\File;
use App\Models\Online;
use App\Models\Cours;
use App\Models\Online_Center;
use App\Models\OptionPaper;
use App\Models\QuestionPaper;
use App\Models\Video;
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
//        $request->validate($this->rules->onlyKey(["Exam", "price", "serial",
//            "durationExam", "id_course", "id_form", "id_poll"], true));
        try {
            DB::beginTransaction();
            $online = Online::create([
                "exam" => $request->exam,
                "price" => $request->price,
                "serial" => $request->serial,
                "durationExam" => $request->durationExam,
                "numberQuestion" => $request->numberQuestion,
                "numberContents" => $request->numberContents,
                "numberHours" => $request->numberHours,
                "id_course" => $request->id_course,
            ]);
            $onlinecenter = Online_Center::create([
                "id_online"=>$online->id,
                 "id_center"=>null,
                "id_course" =>$request->id_course,
            ]);

            foreach ($request['content'] as $inner) {
                $file = $request->file($inner["photo"]);
                        $path = MyApp::uploadFile()->upload($file);
                $content = Content::create([
                    "id_online_center"=>$onlinecenter->id,
                    "numberHours"=>$inner['numberHours'],
                    "numberVideos"=>$inner['numberVideos'],
                    "durationExam"=>$inner['durationExam'],
                    "numberQuestion"=>$inner['numberQuestion'],
                    "photo" => strtolower($path),
                    "name"=>$inner['name'],
                    "rank"=>$inner['0'],
                    "exam"=>$inner['0'],
                ]);

                foreach ($inner['pdfFiles'] as $item) {
                    $file = $request->file($item["file"]);

                            $path = MyApp::uploadFile()->upload($file);
                            $video = File::create([
                                "name" => strtolower($request->name),
                                "file" => strtolower($path),
                              "id_content"=>$content->id,
                        "value" => strtolower($item['value']),
                    ]);

                }
                foreach ($inner['videoFiles'] as $item) {
                    $file = $request->file($item["file"]);
                    $path = MyApp::uploadFile()->upload($file);
                    $Addedop = Video::create([
                        "id_content"=>$content->id,
                        "name"=>$item->name,
                        "rank"=>0,
                        "file"=>$path,
                        "duration"=>$item->duration,

                    ]);

                }

            }




            DB::commit();

            return MyApp::Json()->dataHandle($online, "cours");
        }




                catch (\Exception $e) {
            MyApp::uploadFile()->rollBackUpload();
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return MyApp::Json()->errorHandle("course", $file->getErrorMessage());
    }


    public function updlate(Request $request): JsonResponse
    {

        $request->validate($this->rules->onlyKey(["Exam", "price", "serial",
            "durationExam", "id_course", "id_form", "id_poll"], true));
        $online = Online::where("id", $request->id)->first();
        try {
            DB::beginTransaction();
            $online->update([
                "Exam" => $request->exam,
                "price" => $request->price,
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
        onlyKey(["Exam", "price", "serial",
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
                "price"=>$request->price
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
