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

use App\Http\Resources\CommonCourses;
use App\Models\Center;
use App\Models\Course;
use App\Models\Date;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;



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
            ]);//dd('p');
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
//            $v='';
//            $filePath='';
//            $f='';
            foreach ($request['content'] as $inner) {
                $file = $inner['photo']; // Assuming 'photo' is the key for the uploaded file
                if ($file->isValid()) {
                    // Store the file and get the path
                    $filePath = $file->store('file'); // The file will be stored in the 'public/photo' directory

                    // Create a new Content record
                    $content = Content::create([
                        "id_online_center" => $onlinecenter->id,
                        "numberHours" => $inner['numberHours'],
                        "numberVideos" => $inner['numberVideos'],
                        "durationExam" => $inner['durationExam'],
                        "numberQuestion" => $inner['numberQuestion'],
                        "photo" => strtolower($filePath), // Store the file path in lowercase
                        "name" => $inner['name'],
                        "rank" => $r1,
                        "exam" => $inner['exam'],
                    ]);




                    if($inner['exam']=="1")
                    $courseexam = CourseExame::create([
                        "id_online_center"=>null,
                        "id_content"=>$content->id,
                        "id_exam" =>$inner['id_exam'],
                    ]);
                foreach ($inner['pdfFiles'] as $item) {

                    //         $file = $request->file($item["file"]);
                    $file = $item['file'];
                    if ($file->isValid()) {
                        $f = $file->store('file'); // The file will be stored in the 'public/Uploads' directory

                        $file = File::create([
                            "name" => $item["name"],
                            "file" => strtolower($f),
                            //   "file" => $item["file"],
                            "id_content" => $content->id,
                            "rank" => $r2
                        ]);
                        $r2 = $r2 + 1;

                    }
                }
                foreach ($inner['videoFiles'] as $item) {
                    $file = $item['video'];
                    if ($file->isValid()) {
                        $v=$file->store('file'); // The file will be stored in the 'public/Uploads' directory

                        $video = Video::create([
                            "id_content" => $content->id,
                            "name" => $item["name"],
                            "rank" => $r3,
                            "video" => strtolower($v),
                            //  "video"=>$item["video"],
                            "duration" => $item["duration"],

                        ]);
                        $r3 = $r3 + 1;
                    }

                }
//dd("s");
                    $r3 = 0;
                    $r2 = 0;
                    $r1 = $r1 + 1;
                }
            }

            DB::commit();

            return MyApp::Json()->dataHandle($online, "cours");
       }




       catch (\Exception $e) {
//            MyApp::uploadFile()->deleteFile($v);
//            MyApp::uploadFile()->deleteFile($f);
//            MyApp::uploadFile()->deleteFile($filePath);
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("course", $file->getErrorMessage());
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
