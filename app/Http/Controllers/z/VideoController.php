<?php

namespace App\Http\Controllers;

use App\Models\d6;
use App\Models\d4;
use App\Models\d3;
use App\Models\d2;
use App\Models\Profile;
use App\Models\d1;
use App\Models\Video;
use App\MyApplication\MyApp;
use App\MyApplication\Services\PollFormRuleValidation;
use App\MyApplication\Services\ProfileRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use function SebastianBergmann\Type\returnType;




class VideoController extends Controller
{
    public function show()
    {
        $video = Video::findOrFail(57); // Assuming you have a Video model

        return view('video', compact('video'));

//        return view('video', ['videoPath' => $videoPath]);
      //  $videoUrl = asset('path/video_2024-04-10_10-43-58.mp4');
  //      return view('video', ['videoUrl' => $videoUrl]);
    }




    public function index()
    {

        try {

       //     FFMpeg::openUrl('https://videocoursebuilder.com/lesson-1.mp4');
            FFMpeg::openUrl('https://videocoursebuilder.com/lesson-1.mp4');

        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }



        return MyApp::Json()->errorHandle("poll", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }


    public function Create(Request $request)
    {
      //      return MyApp::Json()->dataHandle($request['body'][0]['question'], "poll");


        //protected $fillable = ['type', 'id_poll_form', 'question', 'kind'];

      //  $request->validate($this->rules->onlyKey(["name","address"],true));
        try {
            DB::beginTransaction();
            $Added = d3::create([
                "name" => strtolower($request['data']['title']),
                "address" => strtolower($request['data']['description']),
                "type" => strtolower($request['data']['type']),
            ]);

//               $Addedid = d2::create([
//                "id_poll"=>$Added->id
//            ]);
           // dd($Added->id);
            foreach ($request['data']['body'] as $inner) {


                $AddedQ = d1::create([
                    "type" => strtolower($inner['select']),
                    "question" => strtolower($inner['question']),
                    "kind" => strtolower($inner['required']),
                    "id_poll_form" => $Added->id,
                ]);
//dd($AddedQ->id);
                foreach ($inner['options'] as $item) {
                    // ['id_question_poll_form', 'answer'];
                    $AddedA = d6::create([
                        "answer" => strtolower($item['value']),
                        "id_question_poll_form" => $AddedQ->id,
                    ]);

                }

            }

            DB::commit();
            return MyApp::Json()->dataHandle($Added, "poll");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }



        return MyApp::Json()->errorHandle("poll", "error");//,$prof->getErrorMessage);

    }


    public function update(Request $request)
    {
        if (d3::query()->where("id", $request->id)->exists()) {
            try {
                DB::beginTransaction();
                $p = d3::where("id", $request->id)->first();
                if ($p) {
                    $p->name = strtolower($request->name);
                    $p->address = strtolower($request->address);
                    $p->save();
                }
                DB::commit();
                return MyApp::Json()->dataHandle("edit successfully", "poll");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("poll", "حدث خطا ما في التعديل ");//,$prof->getErrorMessage);


    }

    public function destroy($id)
    {
        if (d3::query()->where("id", $id)->exists()) {
            try {

                DB::beginTransaction();
                d3::where("id", $id)->delete();
                DB::commit();
                return MyApp::Json()->dataHandle("success", "poll");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("poll", "حدث خطا ما في الحذف ");//,$prof->getErrorMessage);


    }
}
