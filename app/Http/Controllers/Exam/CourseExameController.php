<?php


namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllCourses;
use App\Http\Resources\ExameUserContent;
use App\Models\Content;
use App\Models\CourseExame;
use App\Models\Exame;
use App\Models\Online;
use App\Models\Paper;
use App\Models\Question;
use App\MyApplication\MyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseExameController extends Controller
{
    public function showUserCourse($id_online_center,$id_online)
    {

        DB::beginTransaction();

        try {
            $content = Online::query()
                ->whereHas('onlineCenters', '=', $id_online_center)
                ->where('id', '=', $id_online)->
                with(['onlineCenters' => function ($query) use ($id_online_center,$id_online) {
                $query->where('id',$id_online_center)
                ->where('id_online',$id_online)
                ;
                }])
                ->get();
dd($content);
            if (!$content->isEmpty()) {
                $durationExam = $content[0]['durationExam'];
                $numberQuestion = $content[0]['numberQuestion'];
                // $numberQuestion = 1;
                $exam = $content[0]['exam'];

                if ($exam != 0) {
                    $environments = Exame::with(['questionexamwith' => function ($query) use ($numberQuestion) {
                        $query->limit($numberQuestion);
                    }])
                        ->whereHas('coursexam', function ($query) use ($id_content) {
                            $query->where('id_content', $id_content);
                        })
                        ->get();

                    DB::commit();

                    $data = new ExameUserContent([
                        'data' => $environments->first(),
                        'content' => $content->first(),
                    ]);


                    return response()->json([
                        'data' => ($data),
                    ]);
                } else {
                    DB::commit();
                    return MyApp::Json()->dataHandle('لا يوجدامتحان لهذا المحتوى', "data");
                }
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }




        return MyApp::Json()->errorHandle("data", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);



    }



    public function showUserContent($id_online_center,$id_content)
    {

        DB::beginTransaction();

        try {
            $content = Content::query()
                ->where('id_online_center', '=', $id_online_center)
                ->where('id', '=', $id_content)
                ->get();

            if (!$content->isEmpty()) {
                $durationExam = $content[0]['durationExam'];
                $numberQuestion = $content[0]['numberQuestion'];
               // $numberQuestion = 1;
                $exam = $content[0]['exam'];

                if ($exam != 0) {
                    $environments = Exame::with(['questionexamwith' => function ($query) use ($numberQuestion) {
                        $query->limit($numberQuestion);
                    }])
                        ->whereHas('coursexam', function ($query) use ($id_content) {
                            $query->where('id_content', $id_content);
                        })
                        ->get();

                    DB::commit();

                    $data = new ExameUserContent([
                        'data' => $environments->first(),
                        'content' => $content->first(),
                    ]);


                    return response()->json([
                        'data' => ($data),
                    ]);
                } else {
                    DB::commit();
                    return MyApp::Json()->dataHandle('لا يوجدامتحان لهذا المحتوى', "data");
                }
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }




            return MyApp::Json()->errorHandle("data", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);



        }
}
