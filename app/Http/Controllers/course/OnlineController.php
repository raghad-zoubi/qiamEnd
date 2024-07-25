<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllCourses;
use App\Http\Resources\DetailsOnlineCourses;
use App\Models\Booking;
use App\Models\Content;
use App\Models\CourseExame;
use App\Models\CoursePaper;
use App\Models\Exame;
use App\Models\Favorite;
use App\Models\File;
use App\Models\Online;
use App\Models\Online_Center;
use App\Models\OptionPaper;
use App\Models\Paper;
use App\Models\QuestionPaper;
use App\Models\Rate;
use App\Models\Serial;
use App\Models\Track;
use App\Models\Video;
use App\Models\VideosExame;
use App\MyApplication\MyApp;
use App\MyApplication\Services\CoursesRuleValidation;
use App\Services\FFmpegService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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




    protected $ffmpegService;

    public function __construct(FfmpegService $ffmpegService)
    {
        $this->ffmpegService = $ffmpegService;
      $this->middleware('auth:sanctum')->only(['show','done','still']);
    }
//
//    public function create(Request $request)
//    {
//        try {
//            DB::beginTransaction();
//
//            $online = Online::create([
//                "exam" => strtolower($request->exam),
//                "price" => $request->price,
//                "serial" => $request->serial,
//                "durationExam" => $request->durationExam,
//                "numberQuestion" => $request->numberQuestion,
//                "numberContents" => $request->numberContents,
//                "numberHours" => $request->numberHours,
//                "id_course" => $request->id_course,
//            ]);
//
//            $onlinecenter = Online_Center::create([
//                "id_online" => $online->id,
//                "id_center" => null,
//                "id_course" => $request->id_course,
//            ]);
//
//            if ($request->has('id_form')) {
//                CoursePaper::create([
//                    "id_online_center" => $onlinecenter->id,
//                    "id_paper" => $request->id_form,
//                ]);
//            }
//
//            if ($request->has('id_poll')) {
//                CoursePaper::create([
//                    "id_online_center" => $onlinecenter->id,
//                    "id_paper" => $request->id_poll,
//                ]);
//            }
//
//            if ($request->serial == "1" && $request->has('id_prefix')) {
//                Serial::create([
//                    "id_online_center" => $onlinecenter->id,
//                    "id_course" => $request->id_prefix,
//                ]);
//            }
//
//            $r1 = 0;
//            $r2 = 0;
//            $r3 = 0;
//
//            foreach ($request['content'] as $inner) {
//                $file = $inner['photo']; // Assuming 'photo' is the key for the uploaded file
//                if ($file->isValid()) {
//                    $filePath = $file->store('file'); // The file will be stored in the 'public/file' directory
//                    $content = Content::create([
//                        "id_online_center" => $onlinecenter->id,
//                        "numberHours" => $inner['numberHours'],
//                        "numberVideos" => $inner['numberVideos'],
//                        "durationExam" => $inner['durationExam'],
//                        "numberQuestion" => $inner['numberQuestion'],
//                        "photo" => strtolower($filePath),
//                        "name" => $inner['name'],
//                        "rank" => $r1,
//                        "exam" => $inner['exam'],
//                    ]);
//                    if (isset($inner['id_exam']))
//                        if ($inner['id_exam'] != null) {
//                            CourseExame::create([
//                                "id_online_center" => $onlinecenter->id,
//                                "id_content" => $content->id,
//                                "id_exam" => $inner['id_exam'],
//                            ]);
//                        }
//                    if (isset($inner['pdfFiles']))
//                        foreach ($inner['pdfFiles'] as $item) {
//                            $file = $item['file'];
//                            if ($file->isValid()) {
//                                $f = $file->store('file'); // The file will be stored in the 'public/file' directory
//
//                                File::create([
//                                    "name" => $item["name"],
//                                    "file" => strtolower($f),
//                                    "id_content" => $content->id,
//                                    "rank" => $r2
//                                ]);
//
//                                $r2++;
//                            }
//                        }
//
//                    foreach ($inner['videoFiles'] as $item) {
//                        $file = $item['video'];
//                        //  dd($file->isValid());
//
//                        if ($file->isValid()) {
//                            $v = $file->store('file');
////
//                        $posterPath = $this->extractFrame(substr($v, 5) ); // Extract frame from video
//                            Video::create([
//                                "id_content" => $content->id,
//                                "name" => $item["name"],
//                                "rank" => $r3,
//                                "video" => strtolower($v),
//                                "poster" => $posterPath,
//                                "duration" => $item["duration"],
//                            ]);
//
//                            $r3++;
//                        }
//                        else
//                        return response()->json(['data' => 'error', 'message' => 'Course created successfully']);
//
//                    }
//
//                    $r1++;
//                }
//            }
//
//            DB::commit();
//
//            return response()->json(['data' => $online, 'message' => 'Course created successfully']);
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return response()->json(['error' => $e->getMessage()], 500);
//        }
//        return response()->json(['data' => $online, 'message' => 'Course created Unsuccessfully']);
//
//    }
//    public function extractFrame($video_path)
//    {
//        // Validate the request input
//
//
//        // Get input values
//        $videoPath = public_path('Uploads/file/' . $video_path);
//        // Automatically generate the output image path based on the input video path
//        $outputImagePath = public_path('Uploads/file/poster/' . pathinfo($video_path.'photo', PATHINFO_FILENAME) . '.jpg');
//
//        try {
//            // Call the service to extract the frame
//            $this->ffmpegService->extractFrame($videoPath, '10', $outputImagePath);
//
//            // Generate the URL for the extracted frame
//            $photoUrl = url('uploads/file/poster/' . pathinfo($video_path, PATHINFO_FILENAME) . '.jpg');
//
//            // Return JSON response with the URL of the extracted image
//            return response()->json([
//                'status' => 'success',
//                'message' => 'Frame extracted and saved successfully',
//                'output_image_url' => $photoUrl
//            ]);
//        } catch (\Exception $e) {
//            // Return JSON response with error message
//            return response()->json([
//                'status' => 'error',
//                'message' => 'Failed to extract and save frame: ' . $e->getMessage()
//            ], 500);
//        }
//    }

    public function create(Request $request)
    {
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
                "id_online" => $online->id,
                "id_center" => null,
                "id_course" => $request->id_course,
            ]);

            if ($request->has('id_form')) {
                CoursePaper::create([
                    "id_online_center" => $onlinecenter->id,
                    "id_paper" => $request->id_form,
                ]);
            }

            if ($request->has('id_poll')) {
                CoursePaper::create([
                    "id_online_center" => $onlinecenter->id,
                    "id_paper" => $request->id_poll,
                ]);
            }

            if ($request->serial == "1" && $request->has('id_prefix')) {
                Serial::create([
                    "id_online_center" => $onlinecenter->id,
                    "id_course" => $request->id_prefix,
                ]);
            }

            $r1 = 0;
            $r2 = 0;
            $r3 = 0;

            foreach ($request['content'] as $inner) {
                $file = $inner['photo']; // Assuming 'photo' is the key for the uploaded file
                if ($file->isValid()) {
                    $filePath = $file->store('file'); // The file will be stored in the 'public/file' directory
                    $content = Content::create([
                        "id_online_center" => $onlinecenter->id,
                        "numberHours" => $inner['numberHours'],
                        "numberVideos" => $inner['numberVideos'],
                        "durationExam" => $inner['durationExam'],
                        "numberQuestion" => $inner['numberQuestion'],
                        "photo" => strtolower($filePath),
                        "name" => $inner['name'],
                        "rank" => $r1,
                        "exam" => $inner['exam'],
                    ]);

                    if (isset($inner['pdfFiles'])) {
                        foreach ($inner['pdfFiles'] as $item) {
                            $file = $item['file'];
                            if ($file->isValid()) {
                                $f = $file->store('file'); // The file will be stored in the 'public/file' directory

                                File::create([
                                    "name" => $item["name"],
                                    "file" => strtolower($f),
                                    "id_content" => $content->id,
                                    "rank" => $r2
                                ]);

                                $r2++;
                            }
                        }
                    }

                    foreach ($inner['videoFiles'] as $item) {
                        $file = $item['video'];

                        if ($file->isValid()) {
                            $v = $file->store('file');
                            $posterPath = $this->extractFrame($v); // Extract frame from video

                           $video= Video::create([
                                "id_content" => $content->id,
                                "name" => $item["name"],
                                "rank" => $r3,
                                "video" => strtolower($v),
                                "poster" => $posterPath,
                                "duration" => $item["duration"],
                            ]);
                            if (isset($item['id_exam']) && $item['id_exam'] != null) {
                                //dd($item['id_exam']);
                                VideosExame::create([
//                                    "id_online_center" => $onlinecenter->id,
//                                    "id_content" => $content->id,
                                    "id_video" => $video->id,
                                    "id_exam" => $item['id_exam'],
                                ]);
                            }
                            $r3++;
                        } else {
                            return response()->json(['data' => 'error', 'message' => 'Invalid video file'], 400);
                        }
                    }

                    $r1++;
                }
            }

            DB::commit();

            return response()->json(['data' => $online, 'message' => 'Course created successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function extractFrame($video_path)
    {
        // Get input values
        $videoPath = storage_path('app/public/' . $video_path);
        // Automatically generate the output image path based on the input video path
        $outputImagePath = storage_path('app/public/uploads/file/poster/' . pathinfo($video_path, PATHINFO_FILENAME) . '.jpg');

        if (!file_exists(dirname($outputImagePath))) {
            mkdir(dirname($outputImagePath), 0755, true);
        }

        try {
            // Call the service to extract the frame
            $this->ffmpegService->extractFrame($videoPath, 10, $outputImagePath);

            // Generate the relative path for the extracted frame
            $photoUrl = 'file/poster/' . pathinfo($video_path, PATHINFO_FILENAME) . '.jpg';

            return $photoUrl;
        } catch (\Exception $e) {
            // Handle the exception accordingly, maybe log the error
            throw new \Exception('Failed to extract and save frame: ' . $e->getMessage());
        }
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

    public function showContent($id)
    {
        if (Course::query()->where("id", $id)->exists()) {
            try {

                DB::beginTransaction();
                $contents = Content::select('name', 'id')
                    ->whereHas('OnlineCenter', function ($query) use ($id) {
                        $query->whereHas('online', function ($query) use ($id) {
                            $query->where('id_course', $id);
                        })->where('id_course', $id);
                    })
                    ->get();


                DB::commit();
                return MyApp::Json()->dataHandle($contents, "data");

            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("online", "حدث خطا ما في العرض ");//,$prof->getErrorMessage);


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
                $avgRate = 0;
            }

            $courses = Online_Center::
            with(['course', 'online', 'content'])->
            where('id', $id)->get();
            $courses->each(function ($course) use ($avgRate) {
                $course->avg_rate = $avgRate;
            });


        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return response()->json([
            'course' => DetailsOnlineCourses::collection($courses),
        ]);


    }

    public function done(): JsonResponse
    {
        try {

            $bookinOnlineCenterIds = Booking::where('id_user', Auth::id())
                ->where('done', '1')
                ->pluck('id_online_center');

            $ratesSubquery = Online_Center::leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
                ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
                ->groupBy('online_centers.id')
                ->getQuery();

            $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
                $join->on('online_centers.id', '=', 'subquery.id');
            })
                ->whereIn('online_centers.id', $bookinOnlineCenterIds) // Only fetch bookin courses
                ->with(['course', 'center'])
                ->get();

            return response()->json([
                'data' => AllCourses::collection($courses),
            ]);
        } catch (\Exception $e) {
            // Handle exception
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
        return response()->json([
            'data' => 'حدث خطا ما اعد المحاولة لاحقا',
        ]);

    }

    public function still(): JsonResponse
    {
        try {
            $bookinOnlineCenterIds = Booking::where('id_user', Auth::id())
                ->where('done', '0')
                ->pluck('id_online_center');
                $ratesSubquery = Online_Center::leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
                    ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
                    ->groupBy('online_centers.id','id')
                    ->getQuery();

                $courses = Online_Center::
                joinSub($ratesSubquery, 'subquery', function ($join) {
                    $join->on('online_centers.id', '=', 'subquery.id');
                })->
                whereIn('online_centers.id', $bookinOnlineCenterIds->toArray())
                    ->where('id_center', null) // Pass array of values
                ->with(['course'])
                    ->get();






//                $content = Content::whereIn('id_online_center', $bookinOnlineCenterIds->toArray())
//                    ->orderBy('rank', 'desc')
//                    ->first();
//                if ($content) {
//                    $video = Video::where('id_content', $content->id)
//                        ->orderBy('rank', 'desc')
//                        ->first();
//                    if ($video) {
//
//                        $can = Track::where('id_booking', $booking->id)
//                            ->where('done', '1')
//                            ->where('id_video', $video->id)
//                            ->exists();
//
//







                return response()->json([
                   'data' => AllCourses::collection($courses),
                    //  'data' => ($courses),
                ]);

        } catch (\Exception $e) {
            // Handle exception
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'data' => 'حدث خطا ما اعد المحاولة لاحقا',
        ]);

    }
}






