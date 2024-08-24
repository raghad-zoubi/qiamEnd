<?php

namespace App\Http\Controllers\course;

use Illuminate\Support\Facades\Storage;

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
use function League\Flysystem\isFile;
use function Symfony\Component\CssSelector\Parser\isString;


/**
 * @property CoursesRuleValidation rules
 * @property AllCourses obj
 */
class CoursController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:sanctum"]);//, "multi.auth:2|1|0"]);
        $this->rules = new CoursesRuleValidation();
    }

    public function indexname(): JsonResponse
    {
        $course = Course::query()->get(['id', 'name']);
        return MyApp::Json()->dataHandle($course, "data");
    }


    public function index(): JsonResponse
    {
        $course = Course::query()->get();
        return MyApp::Json()->dataHandle($course, "data");
    }

    public function create(Request $request): JsonResponse
    {
        if (isset($request['photo']) && $request['photo']->isValid()) {
            try {
                $photoPath = $request->file('photo')->store('file'); // The file will be stored in the 'public/Uploads' directory

                DB::beginTransaction();
                //   dd($path);
                $courceAdded = Course::create([
                    "about" => strtolower($request->about),
                    "name" => strtolower($request->name),
                    "photo" => ($photoPath),
                    "teacher" => ($request->teacher),
                    "text" => ($request->text),
                ]);
                DB::commit();

                return MyApp::Json()->dataHandle($courceAdded, "data");
            } catch (\Exception $e) {
                MyApp::uploadFile()->deleteFile($photoPath);
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            return MyApp::Json()->errorHandle("data", 'حدث خطا ما اعد المحاوله لاحقا');
        }
    }

    public function update($id, Request $request): JsonResponse
    {
//        $request->validate($this->rules->onlyKey(["name", "photo", "about"], true));
        $file = Course::where("id", $id)->first();
        if (!is_string($request->photo)) {
            $oldPath = $file->photo;

            $newFile = $request->file("photo");
            if ($newFile->isValid()) {
                try {
                    DB::beginTransaction();

                    $photoPath = $newFile->store('file'); // The file will be stored in the 'public/Uploads' directory

                    $file->update([
                        "teacher" => ($request->teacher),
                        "text" => ($request->text),
                        "about" => strtolower($request->about),
                        "name" => strtolower($request->name),
                        "photo" => $photoPath,
                    ]);

                    if (MyApp::uploadFile()->deleteFile($oldPath)) {
                        DB::commit();
                        return MyApp::Json()->dataHandle("Successfully updated course.", "data");
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw new \Exception($e->getMessage(), $e->getCode());
                }
            }}
        else   if (is_string($request->photo)){
            try {
                DB::beginTransaction();
                $file->update([
                    "teacher" => ($request->teacher),
                    "text" => ($request->text),
                    "about" => strtolower($request->about),
                    "name" => strtolower($request->name),
                    "photo" =>strtolower($request->photo),
                ]);

                DB::commit();
                return MyApp::Json()->dataHandle("Successfully updated course.", "data");

            } catch (\Exception $e) {
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
                if (MyApp::uploadFile()->deleteFile('photo/', $temp_path, 'Uploads/file/')) ;
                {
                    DB::commit();
                    return MyApp::Json()->dataHandle("Successfully deleted  .", "data");
                }
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


    }

    public function displaydetils($id): JsonResponse
    {
        try {
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
            return MyApp::Json()->dataHandle($courses, "data");

        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


    }


//--------user home
//    public function common(): JsonResponse
//    {
//        try {
//            $ratesSubquery = Online_Center::leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
//                ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
//                ->
//                groupBy('online_centers.id')
//                ->getQuery();
//            $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
//                $join->on('online_centers.id', '=', 'subquery.id');
//            })->with(['course', 'online', 'center'])
//                ->orderBy('subquery.avg_rate', 'desc')
//                ->paginate(10);
//            return response()->json([
//                'data' => CommonCourses::collection($courses),
//            ]);
//        } catch (\Exception $e) {
//
//            DB::rollBack();
//            throw new \Exception($e->getMessage());
//        }
//
//
//        return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);
//
//
//    }


    public function common(): JsonResponse
    {
        try {
            // Subquery to calculate the average rating for each online_center
            $ratesSubquery = Online_Center::leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
                ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
                ->groupBy('online_centers.id')
                ->getQuery();

            // Main query with necessary filtering
            $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
                $join->on('online_centers.id', '=', 'subquery.id');
            })
                ->with(['course'])
                ->whereHas('online', function ($query) {
                    $query->where('isopen', '=', '1');
                })
                ->orWhereHas('center', function ($query) {
                    $query->where('start', '<=', now())
                        ->where('end', '>=', now());
                }) // Include courses associated with a center that are active
                ->orderBy('subquery.avg_rate', 'desc')
                ->paginate(10);

            return response()->json([
                'data' => CommonCourses::collection($courses),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }


    public function all(): JsonResponse
    {
        try {
            $ratesSubquery = Online_Center::leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
                ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
                ->groupBy('online_centers.id')
                ->getQuery();

            $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
                $join->on('online_centers.id', '=', 'subquery.id');
            })
            ->with(['course'])
                ->whereHas('online', function ($query) {
                    $query->where('isopen', '=', '1');
                })
                ->orWhereHas('center', function ($query) {
                    $query->where('start', '<=', now())
                        ->where('end', '>=', now());
                }) ->paginate(20);

            return response()->json([
                'data' => AllCourses::collection($courses),
            ]);
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


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
                    ->with(['course'])
                    ->whereHas('online', function ($query) {
                        $query->where('isopen', '=', '1');
                    })


                    ->get();
            else if ($type == 'center')
                $mytime = Carbon::now();

                $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
                    $join->on('online_centers.id', '=', 'subquery.id');
                })->where('online_centers.id_online', '=', null)
                    ->with(['course'])
                    ->WhereHas('center', function ($query) use ($mytime) {
                        $query->where('start', '<',  $mytime->toDateTimeString())
                            ->where('end', '>', $mytime->toDateTimeString());})
                    ->get();




            return response()->json([
                'data' => AllCourses::collection($courses),
            ]);
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


    }

    public function search(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->input('searchTerm');
            $by = $request->input('by');

            if ($by == 'الدورة') {
                $courses = DB::table('courses')
                    ->leftJoin('online_centers', 'courses.id', '=', 'online_centers.id_course')
                    ->leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
                    ->select(
                        'courses.id',
                        'courses.name',
                        'courses.about',  // Add all other columns you need from the 'courses' table
                        'courses.photo',
                        'courses.teacher',
                        'online_centers.id as online_center_id',
                        DB::raw('COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
                    )
                    ->where('courses.name', $searchTerm)
                    ->orWhere('courses.name', 'like', '%' . $searchTerm . '%')
                    ->groupBy(
                        'courses.id',
                        'courses.name',
                        'courses.about',  // Add all other columns you need from the 'courses' table
                        'courses.photo',
                        'courses.teacher',
                        'online_centers.id'
                    )
                    ->get();


            }
            else if ($by == 'المدرس') {
                $courses = DB::table('courses')
                    ->leftJoin('online_centers', 'courses.id', '=', 'online_centers.id_course')
                    ->leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
                    ->select(
                        'courses.id',
                        'courses.name',
                        'courses.about',  // Add all other columns you need from the 'courses' table
                        'courses.photo',
                        'courses.teacher',
                        'online_centers.id as online_center_id',
                        DB::raw('COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
                    )
                    ->where('courses.teacher', $searchTerm)
                    ->orWhere('courses.teacher', 'like', '%' . $searchTerm . '%')
                    ->groupBy(
                        'courses.id',
                        'courses.name',
                        'courses.about',  // Add all other columns you need from the 'courses' table
                        'courses.photo',
                        'courses.teacher',
                        'online_centers.id'
                    )
                    ->get();


            }


            return response()->json([
                'data' => ($courses),
            ]);
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


    }

}
