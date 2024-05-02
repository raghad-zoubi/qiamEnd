<?php

namespace App\Http\Controllers\advisor;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailsCenterCourses;
use App\Http\Resources\IndexTypeAdvisor;
use App\Http\Resources\ShowAdviser;
use App\Models\Adviser;
use App\Models\Course;
use App\Models\d3;
use App\Models\Date;
use App\Models\Reserve;
use App\Models\User;
use App\MyApplication\MyApp;
use App\MyApplication\Services\AdviserRuleValidation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdviserController extends Controller
{
    public function __construct()
    {
//        $this->middleware(["auth:sanctum"]);
//        $this->rules = new AdviserRuleValidation();
    }
//عرض الكل
    public function index()
    {

        try {

            DB::beginTransaction();
            $adviserGet = Adviser::query()->
            //  with('user')->
            get();
            DB::commit();

            //   return response()->json([
            //   '$adviserGet' =>DetailsCenterCourses::collection($courses),
            //     '$adviserGet' =>($adviserGet),
            // ]);

            return MyApp::Json()->dataHandle($adviserGet, "adviser");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("adviser", "حدث خطا ما في عرض  لديك ");//,$prof->getErrorMessage);

    }

//عرض مستشار +موعيد وحجوزات اليوم
    public function show($id_adviser)
    {
        try {

            DB::beginTransaction();
            $date = Carbon::now();
            $d = $date->format("Y-m-d");
            $result = Adviser::where('id', $id_adviser)
                ->with(['date' => function ($query) use ($d) {
                    $query->where('day', $d)
                        ->with('reserve');
                }])
                ->get();
            DB::commit();
            return response()->json([
                'result' => ShowAdviser::collection($result),
            ]);        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        return MyApp::Json()->errorHandle("Adviser", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

    public function create(Request $request)
    {// raghad11
        if (isset($request['photo']) && $request['photo']->isValid()) {
            try {
                // Check if a photo file was uploaded
                // Generate a unique file name;
                $photoPath = $request->file('photo')->store('file'); // The file will be stored in the 'public/Uploads' directory


                DB::beginTransaction();

                $adviserAdded = Adviser::create([
                    "about" => strtolower($request->about),
                    "type" => strtolower($request->type),
                    "name" => strtolower($request->name),
                    "photo" => $photoPath

                ]);
                if ($request->has('date') && $request->date!= '[]'){
                if (isset($request['date']))
                    {$dataArray = json_decode(($request->date ), true);
                        foreach ($dataArray as $index => $date){
                        if (is_array($date) && isset($date['day']) && //raghad
                            isset($date['times']) && is_array($date['times'])) {
                            $day = $date['day'];
                            foreach ($date['times'] as $time) {
                                {$dateAdded = Date::create([
                                        "from" => ($time['from']),
                                        "to" => ($time['to']),
                                        "day" => $day,
                                        "id_adviser" => $adviserAdded->id
                                    ]);}}
                        }}}}

                DB::commit();
                return MyApp::Json()->dataHandle($adviserAdded, "Adviser");
            }
         catch (\Exception $e) {
             MyApp::uploadFile()->deleteFile($photoPath) ;             DB::rollBack();
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
    else


    return MyApp::Json()->errorHandle("adviser", "حدث خطا ما في الاضافة  لديك ");//,$prof->getErrorMessage);

}

        public
        function update($id,Request $request)
        {
            if (Adviser::query()->where("id", $id)->exists()) {
                $ad = Adviser::where("id", $id)->first();
                $oldPath = $ad->photo;
                //  dd($newFile);
                $newFile = $request->file("photo");
                if ($newFile->isValid()) {
                    try {
                        DB::beginTransaction();

                        $photoPath = $newFile->store('file'); // The file will be stored in the 'public/Uploads' directory

                        if ($ad) {
                            $ad->about = strtolower($request->about);
                            $ad->name = strtolower($request->name);
                            $ad->type = strtolower($request->type);
                            $ad->id_user = ($request->id_user)??null;
                            $ad->photo = ($photoPath);
                            $ad->save();
                        }


                       if (MyApp::uploadFile()->deleteFile($oldPath)) {


                           DB::commit();
                            return MyApp::Json()->dataHandle("Successfully updated course.", "data");
                       }
                    } catch (\Exception $e) {

                        DB::rollBack();
                        throw new \Exception($e->getMessage());
                    }

                }

            } else

                return MyApp::Json()->errorHandle("adviser", "حدث خطا ما في تعديل  لديك ");//,$prof->getErrorMessage);


        }

        public
        function delete($id)
        {
            if (Adviser::query()->where("id", $id)->exists()) {
                try {

                    DB::beginTransaction();
                    $ad = Adviser::where("id", $id)->first();
                    $oldPath = $ad->photo;
                    if (MyApp::uploadFile()->deleteFile($oldPath));
                    { Adviser::where("id", $id)->delete();
                    DB::commit();
                    return MyApp::Json()->dataHandle("success deleted", "adviser");
                }} catch (\Exception $e) {

                    DB::rollBack();
                    throw new \Exception($e->getMessage());
                }

            } else

                return MyApp::Json()->errorHandle("adviser", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


        }

        //user
        public function display($type)
        {

            try {

                DB::beginTransaction();
                $adviserGet = Adviser::query()->
                //   with('user')->
                where('type', '=', $type)->
                get();
                DB::commit();
//
                return response()->json([
                    //    'course' =>DetailsCenterCourses::collection($courses),
                    'adviserGet' => ($adviserGet),
                ]);

                //     return MyApp::Json()->dataHandle(IndexTypeAdvisor::Collection($adviserGet), "adviser");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }


            return MyApp::Json()->errorHandle("adviser", "حدث خطا ما في عرض  لديك ");//,$prof->getErrorMessage);

        }
    }
