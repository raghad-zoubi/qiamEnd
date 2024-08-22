<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailsCenterCourses;
use App\Http\Resources\DetailsOnlineCourses;
use App\Models\Center;
use App\Models\CoursePaper;
use App\Models\d4;
use App\Models\Online_Center;
use App\Models\Rate;
use App\MyApplication\MyApp;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

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



                    if ($request->has('id_form'))
                        if (!isNull($request->id_form)){
                            $onlinepaper = CoursePaper::create([
                                "id_online_center" => $onlinecenter->id,
                                "id_paper" => $request->id_form,

                            ]);
                    }

//                if($request->has('id_poll'))

                if ($request->has('id_poll'))
                    if (!isNull($request->id_poll)) {
                        $onlinepaper = CoursePaper::create([
                            "id_online_center" => $onlinecenter->id,
                            "id_paper" => $request->id_poll,

                        ]);
                    }
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

    ////USER
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
            with(['course','center',])->
            where('id',$id)->
            get()
                ->map(function ($course) {
                    // Check if the user has favorited this course
                    $course->fav = \DB::table('favorites')
                        ->where('id_user', auth()->id())
                        ->where('id_online_center', $course->id)
                        ->exists() ? 1 : 0; // Set fav to 1 if exists, otherwise 0

                    // Check if the user has booked this course
                    $booking = \DB::table('booking')
                        ->where('id_user', auth()->id())
                        ->where('id_online_center', $course->id)
                        ->where('status', '1')
                        ->first(); // Get the first booking record

                    if ($booking) {
                        $course->booked = 1; // Set booked to 1 if exists
                        $course->done = $booking->done; // Assuming 'done' is a column in your booking table

                        // Check if done is 1
                        if ($course->done == 1) {
                            $course->cer = \DB::table('user_certificate')
                                ->where('id_user', auth()->id())
                                ->where('id_online_center', $course->id)
                                ->exists() ? 1 : 0; // Set cer to 1 if exists, otherwise 0
                        }
                        else {
                            // If done is not 1, get the value of can (assuming can is a column in booking)
                            //  $course->can = $booking->can; // Adjust this line as needed
                        }
                    } else {
                        $course->booked = 0; // Set booked to 0 if no booking exists
                        $course->done = 0; // No booking, so done is null
                        $course->cer = 0; // No booking, so cer is also null
                        $course->can = 0; // Or set it to some default value if needed
                    }

                    return $course;
                });
            $courses->each(function ($course) use ($avgRate) {
                $course->avg_rate = $avgRate;
            });


            return response()->json([
                'course' =>DetailsCenterCourses::collection($courses),
                //  'course' =>($courses),
            ]);
        }catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }




    }

}
