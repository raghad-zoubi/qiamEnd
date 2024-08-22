<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentUserBook;
use App\Http\Resources\ExameUserContent;
use App\Models\Booking;
use App\Models\Content;
use App\Models\Online;
use App\Models\Online_Center;
use App\Models\Track;
use App\Models\TrackContent;
use App\Models\Video;
use App\MyApplication\MyApp;
use App\Services\FFmpegService;
use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function Intervention\Image\Typography\add;

class ContentController extends Controller
{protected $ffmpegService;

    public function __construct(FFmpegService $ffmpegService)
    {        $this->ffmpegService = $ffmpegService;

        $this->middleware('auth:sanctum');
    }

    public function show($id_content)
    {
        $content = Content::where('id', $id_content)->first();
        if (!$content) {

            return response()->json([
                "data" =>"Content not found"]);
        }
        else {
            $booking = Booking::where([
                'id_online_center' => $content->id_online_center,
                'id_user' => Auth::id(),
                'status' => "1",
            ])->first();
            if ($booking==null) {
                return response()->json([
                    "data" => "Unauthorized access to content"

                ]);    } else {
                if ($content->rank == '0') {
                    $content_data = Content::query()->
                    where('id', $id_content)->with(['video', 'file','trackcontent'])->get();
                 $data = new ContentUserBook($content_data[0]);
                    return response()->json([
                        "data" => $data

                    ]);
                }
                if ($content->rank != '0') {
                    $rank = $content->rank - 1;

                    $v = Content::query()->where('id_online_center', $content->id_online_center)
                        ->where('rank', $rank)
                        ->with('video')
                        ->first();

                    if ($v!=null) {
                        if ($v->exam == '0') {
                            $idVideos = $v->video->pluck('id')->toArray();
                            $can = Track::where('id_booking', $booking->id)
                                ->where('done', '1')
                                ->whereIn('id_video', $idVideos)
                                ->exists();
                            if ($can) {
                                $content_data = Content::query()->
                                where('id', $id_content)->with(['video', 'file','trackcontent'])->get();
                                $data = new ContentUserBook($content_data[0]);
                                return response()->json([
                                    "data" => $data

                                ]);
                            } else {
                                return response()->json([
                                    "data" => "Unauthorized access to content"
                                ]);
                            }

                        }
                        //    else//معالجة حالة الامتحان

                        else if ($v->exam == '1') {
                            $idVideos = $v->video->pluck('id')->toArray();
                            $can = Track::where('id_booking', $booking->id)
                                ->where('done', '1')
                                ->whereIn('id_video', $idVideos)
                                ->exists();
                            if ($can) {

                                $c=Content::query()->where('id', $id_content)->first();
                                $r=$c->rank-1;
                                $c2=Content::query()->where('id_online_center', $c->id_online_center)
                                ->where('rank', $r)->first();
                                $can2 = TrackContent::where('id_booking', $booking->id)->
                                     where('id_content', $c2->id)
                                    ->exists();

                                if ($can2) {
                                $content_data = Content::query()->
                                where('id', $id_content)->with(['video', 'file','trackcontent'])->get();
                                $data = new ContentUserBook($content_data[0]);
                                return response()->json([
                                    "data" => $data

                                ]);
                            } else {
                                    return response()->json([
                                        "data" => "Unauthorized access to content"
                                    ]);
                                }
                            }else {
                                return response()->json([
                                    "data" => "Unauthorized access to content"
                                ]);
                            }

                        }

                    }

                }

            }
        }
        return response()->json([
            "data" => "حدث خطا ما اعد المحاولة لاحقا"]);

    }

    public function convertVideo()
    {
        $inputPath = storage_path('app/public/input/video.mp4');
        $outputPath = storage_path('app/public/output/video.mp4');

        return $this->ffmpegService->convertVideo($inputPath, $outputPath);
    }

    public function getVideoInfo()
    {
        $videoPath = storage_path('app/public/input/video.mp4');
        $videoInfo = $this->ffmpegService->getVideoInfo($videoPath);

        return response()->json($videoInfo);
    }

    public function extractFrame(Request $request)
    {
        // Validate the request input
        $request->validate([
            'video_path' => 'required|string',
        ]);

        // Get input values
        $videoPath = public_path('Uploads/file/' . $request->input('video_path'));
        // Automatically generate the output image path based on the input video path
        $outputImagePath = public_path('Uploads/file/poster/' . pathinfo($request->input('video_path').'photo', PATHINFO_FILENAME) . '.jpg');

        try {
            // Call the service to extract the frame
            $this->ffmpegService->extractFrame($videoPath, '10', $outputImagePath);

            // Generate the URL for the extracted frame
            $photoUrl = url('uploads/file/poster/' . pathinfo($request->input('video_path'), PATHINFO_FILENAME) . '.jpg');

            // Return JSON response with the URL of the extracted image
            return response()->json([
                'status' => 'success',
                'message' => 'Frame extracted and saved successfully',
                'output_image_url' => $photoUrl
            ]);
        } catch (\Exception $e) {
            // Return JSON response with error message
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to extract and save frame: ' . $e->getMessage()
            ], 500);
        }
    }
public function extractFrameg($video_path)
    {
        // Validate the request input


        // Get input values
        $videoPath = public_path('Uploads/file/' . $video_path);
        // Automatically generate the output image path based on the input video path
        $outputImagePath = public_path('Uploads/file/poster/' . pathinfo($video_path.'photo', PATHINFO_FILENAME) . '.jpg');

        try {
            // Call the service to extract the frame
            $this->ffmpegService->extractFrame($videoPath, '10', $outputImagePath);

            // Generate the URL for the extracted frame
            $photoUrl = url('uploads/file/poster/' . pathinfo($video_path, PATHINFO_FILENAME) . '.jpg');

            // Return JSON response with the URL of the extracted image
            return response()->json([
                'status' => 'success',
                'message' => 'Frame extracted and saved successfully',
                'output_image_url' => $photoUrl
            ]);
        } catch (\Exception $e) {
            // Return JSON response with error message
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to extract and save frame: ' . $e->getMessage()
            ], 500);
        }
    }

}
