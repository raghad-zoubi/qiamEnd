<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllCourses;
use App\Http\Resources\CommonCourses;
use App\Models\Center;
use App\Models\Course;
use App\Models\Date;
use App\Models\File;
use App\Models\Online;
use App\Models\Online_Center;
use App\Models\Rate;
use App\MyApplication\MyApp;
use App\MyApplication\Services\CoursesRuleValidation;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


/**
 * @property CoursesRuleValidation rules
 * @property AllCourses obj
 */
class CoursController extends Controller
{
    public function __construct()
    {
        //$this->middleware(["auth:sanctum"]);
        $this->rules = new CoursesRuleValidation();
    }
    public function indexname(): JsonResponse
    {
        $course = Course::query()->get(['id','name']);
        return MyApp::Json()->dataHandle($course, "course");
    }


    public function index(): JsonResponse
    {
        $course = Course::query()->get();
        return MyApp::Json()->dataHandle($course, "course");
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["name", "photo", "about"], true));
        $file = $request->file("photo");
        if ($file->isValid()) {
            try {
                DB::beginTransaction();
                $path = MyApp::uploadFile()->upload($file);
                //   dd($path);
                $courceAdded = Course::create([
                    "about" => strtolower($request->about),
                    "name" => strtolower($request->name),
                    "photo" => strtolower($path),
                ]);
                DB::commit();

                return MyApp::Json()->dataHandle($courceAdded, "cours");
            } catch (\Exception $e) {
                MyApp::uploadFile()->rollBackUpload();
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            return MyApp::Json()->errorHandle("course", $file->getErrorMessage());
        }
    }

    public function update($id,Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["name", "photo", "about"], true));
        $file = Course::where("id", $id)->first();
        $oldPath = $file->photo;
        $newFile = $request->file("photo");
        //  dd($newFile);
        if ($newFile->isValid()) {
            try {
                DB::beginTransaction();
                $newPath = MyApp::uploadFile()->upload($newFile);
                $file->update([
                    "about" => strtolower($request->about),
                    "name" => strtolower($request->name),
                    "photo" => $newPath,
                ]);
                MyApp::uploadFile()->deleteFile($oldPath);
                DB::commit();
                return MyApp::Json()->dataHandle("Successfully updated course.", "message");
            } catch (\Exception $e) {
                MyApp::uploadFile()->rollBackUpload();
                DB::rollBack();
                throw new \Exception($e->getMessage(), $e->getCode());
            }
        }
        return MyApp::Json()->errorHandle("file", $newFile->getErrorMessage());
    }

    public function delete($id): JsonResponse
    {
        if (Course::query()->where("id", $id)->exists()) {
            try {


                $file = Course::where("id", $id)->first();

                DB::beginTransaction();
                $temp_path = $file->photo;
                $file->delete();

                if (MyApp::uploadFile()->deleteFile($temp_path)) {
                    DB::commit();
                    return MyApp::Json()->dataHandle("Successfully deleted file .", "message");
                }
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


    }

    public function displaydetils($id): JsonResponse
    {try {
        $ratesSubquery = Online_Center::leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
            ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
            ->
            groupBy('online_centers.id')
            ->getQuery();
        $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
            $join->on('online_centers.id', '=', 'subquery.id');
        })->with(['course', 'online', 'center'])
            ->orderBy('subquery.avg_rate', 'desc')
            ->paginate(10);
        return MyApp::Json()->dataHandle($courses, "date");

    }
    catch (\Exception $e) {

        DB::rollBack();
        throw new \Exception($e->getMessage());
    }


return MyApp::Json()->errorHandle("date", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


    }


//--------user home
    public function common(): JsonResponse
    {
       try{ $ratesSubquery = Online_Center::leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
            ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
            ->
            groupBy('online_centers.id')
            ->getQuery();
        $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
            $join->on('online_centers.id', '=', 'subquery.id');
        })->with(['course', 'online', 'center'])
            ->orderBy('subquery.avg_rate', 'desc')
            ->paginate(10);
        return response()->json([
            'date' => CommonCourses::collection($courses),
        ]);
    }
    catch (\Exception $e) {

        DB::rollBack();
        throw new \Exception($e->getMessage());
    }


return MyApp::Json()->errorHandle("date", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


}

    public function all(): JsonResponse
    {//     $rates = Online_Center::
//        leftJoin('rates', 'online_centers.id', '=', 'rates.id')
//       ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
//       ->groupBy('online_centers.id');
//
//        $courses= Online_Center::
//       with(['course', 'online', 'center']) ->paginate(10);

try {
    $ratesSubquery = Online_Center::leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
        ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
        ->groupBy('online_centers.id')
        ->getQuery();

    $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
        $join->on('online_centers.id', '=', 'subquery.id');
    })->with(['course', 'online', 'center'])->paginate(10);


    return response()->json([
        'date' => AllCourses::collection($courses),
    ]);
}
catch (\Exception $e) {

        DB::rollBack();
        throw new \Exception($e->getMessage());
    }


            return MyApp::Json()->errorHandle("date", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);



    }

    public function each($type): JsonResponse
    {
        try {
            $ratesSubquery = Online_Center::leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
                ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
                ->groupBy('online_centers.id')
                ->getQuery();
            if ($type == 'online')
                $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
                    $join->on('online_centers.id', '=', 'subquery.id');
                })->where('online_centers.id_center', '=', null)
                    ->with(['course', 'online', 'center'])
                    ->get();
            else if ($type == 'center')

                $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
                    $join->on('online_centers.id', '=', 'subquery.id');
                })->where('online_centers.id_online', '=', null)
                    ->with(['course', 'center'])->get();

            return response()->json([
                'date' => AllCourses::collection($courses),
            ]);
        }catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


return MyApp::Json()->errorHandle("date", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);



}

}
