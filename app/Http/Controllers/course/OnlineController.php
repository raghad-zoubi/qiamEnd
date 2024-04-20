<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllCourses;
use App\Http\Resources\DetailsOnlineCourses;
use App\Models\Content;
use App\Models\CourseExame;
use App\Models\CoursePaper;
use App\Models\File;
use App\Models\Online;
use App\Models\Online_Center;
use App\Models\OptionPaper;
use App\Models\Paper;
use App\Models\QuestionPaper;
use App\Models\Rate;
use App\Models\Serial;
use App\Models\Video;
use App\MyApplication\MyApp;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function League\Flysystem\has;

/**
 * @property CoursesRuleValidation rules
 */
class OnlineController extends Controller
{

    public function __construct()
    {
    //    $this->middleware(["auth:sanctum"]);
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
                "exam" => strtolower($request->exam),
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
            if($request->serial=="1"&& $request->has('id_prefix'))
                $serial=Serial::create([
                    "id_online_center"=>$onlinecenter->id,
                    "id_course" =>$request->id_prefix,
                ]);

            if($request->exam=="1"&& $request->has('id_exam'))
                $courseexam = CourseExame::create([
                    "id_online_center"=>$onlinecenter->id,
                 "id_content"=>null,
                "id_exam" =>$request->id_exam,
            ]);

$r2=0;
$r1=0;
$r3=0;
            foreach ($request['content'] as $inner) {

                $file = $request->file($inner["photo"]);
                $path = MyApp::uploadFile()->upload($file);
                $content = Content::create([
                    "id_online_center"=>$onlinecenter->id,
                    "numberHours"=>$inner['numberHours'],
                    "numberVideos"=>$inner['numberVideos'],
                    "durationExam"=>$inner['durationExam'],
                    "numberQuestion"=>$inner['numberQuestion'],
                  //  "photo" => ($inner['photo']),
                    "photo" => ($inner['$path']),
                    "name"=>$inner['name'],
                    "rank"=>$r1,
                    "exam"=>$inner['exam'],
                ]);
                if($inner['exam']=="1")
                    $courseexam = CourseExame::create([
                        "id_online_center"=>null,
                        "id_content"=>$content->id,
                        "id_exam" =>$inner['id_exam'],
                    ]);
                foreach ($inner['pdfFiles'] as $item) {
                    $file = $request->file($item["file"]);

                            $path = MyApp::uploadFile()->upload($file);
                            $file = File::create([
                                "name" =>$item["name"],
                                "file" => $item["$path"],
                             //   "file" => $item["file"],
                              "id_content"=>$content->id,
                            "rank" => $r2
                    ]);
                    $r2=$r2+1;

                }
                foreach ($inner['videoFiles'] as $item) {
                    $file = $request->file($item["video"]);
                    $path = MyApp::uploadFile()->upload($file);
                    $video = Video::create([
                        "id_content"=>$content->id,
                        "name"=>$item["name"],
                        "rank"=>$r3,
                        "video"=>$item["$path"],
                      //  "video"=>$item["video"],
                        "duration"=>$item["duration"],

                    ]);
                    $r3=$r3+1;

                }

                $r3=0;
$r2=0;
$r1=$r1+1;
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
    ////USER HOME

    public function show($id): JsonResponse
    {


        try {
     DB::beginTransaction();
            $ratesSubquery = Rate::selectRaw('COALESCE(SUM(value) / COUNT(value), 0) as avg_rate')
                ->where('id_online_center', $id)->get();
            if ($ratesSubquery->isNotEmpty()) {
                $avgRate = $ratesSubquery[0]->avg_rate;
            } else {
                $avgRate=0;
            }

            $courses = Online_Center::
            with(['course','online','content'])->
                where('id',$id)->get();
            $courses->each(function ($course) use ($avgRate) {
                $course->avg_rate = $avgRate;
            });


    }catch (\Exception $e) {

    DB::rollBack();
        throw new \Exception($e->getMessage());
    }


        return response()->json([
               'course' =>DetailsOnlineCourses::collection($courses),
        ]);


    }

}
