<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentUserBook;
use App\Http\Resources\DetailsOnlineCourses;
use App\Http\Resources\VideoCourses;
use App\Models\Booking;
use App\Models\Content;
use App\Models\Video;
use App\Models\Online_Center;
use App\Models\Rate;
use App\Models\Track;
use App\Services\FFmpegService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function show($id_video)
    {
        $video = Video::query()->where('id', $id_video)->first();
        if (!$video) {

            return response()->json([
                "data" =>"Video not found"]);
        }
        else {
            $id_online_center=Content::query()->where('id',$video->id_content)->first();
       $booking = Booking::query()->where([
                'id_online_center' => $id_online_center->id_online_center,
                'id_user' => Auth::id(),
                'status' => "1",
            ])->first();

            if (!$booking) {
                return response()->json([
                    "data" => "Unauthorized access to video"
                ]);    }
            else {
                     if ($track=Track::query()
                         ->where('id_booking',$booking->id)
                         ->where('id_video', $id_video)->
                         exists())
                {
                    $video_data = Video::query()->
                    where('id', $id_video)->select([
                        "id","id_content","name","duration","video",
                    ])->get();
                    return response()->json([
                        "data" => $video_data

                    ]);
                }
                 else {
                     if ($video->rank == '0') {
                         $video_data = Video::query()->
                         where('id', $id_video)->select([
                             "id", "id_content", "name", "duration", "video",
                         ])->get();
                         $addvideo=Track::query()->create([
                              'id_video'=>$id_video,
                             'id_booking'=>$booking->id,
                              'endTime'=>'00:00:00',
                              'done'=>'0',
                          ]);

                         return response()->json([
                             "data" => $video_data

                         ]);
                     }
                     else if ($video->rank != '0') {
                         $rank = $video->rank - 1;
                         $v = Video::query()->where('id_content', $video->id_content)
                             ->where('rank', $rank)
                             ->first();

                         if ($v) {

                             $can = Track::query()->where('id_booking', $booking->id)
                                 ->where('done', '1')
                                 ->where('id_video', $v->id)
                                 ->exists();
                             if ($can) {
                                 $video_data = Video::query()->
                                 where('id', $id_video)->select([
                                     "id", "id_content", "name", "duration", "video",
                                 ])->get();

                                 $addvideo=Track::query()->create([
                                     'id_video'=>$id_video,
                                     'id_booking'=>$booking->id,
                                     'endTime'=>'00:00:00',
                                     'done'=>'0',
                                 ]);

                                 return response()->json([
                                     "data" => $video_data

                                 ]);

                             } else {
                                 return response()->json([
                                     "data" => "Unauthorized access to video"
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
    public function afterVideo($id_video,$endTime)
    {
        $video = Video::query()->where('id', $id_video)->first();
        if (!$video) {

            return response()->json([
                "data" => "Video not found"]);
        } else {
            $id_online_center = Content::query()->where('id', $video->id_content)->first();
            $booking = Booking::query()->where([
                'id_online_center' => $id_online_center->id_online_center,
                'id_user' => Auth::id(),
                'status' => "1",
            ])->first();

            if (!$booking) {
                return response()->json([
                    "data" => "Unauthorized access to video"
                ]);
            } else {
                $track = Track::query()
                    ->where('id_video', $video->id)
                    ->where('id_booking', $booking->id)
                    ->first();
                if ($track) {
                    if ($track->done != '1') {
                        if ($endTime >= $video->duration)
                            $done = '1';
                        else
                            $done = '0';
                        $track->done = ($done);
                        $track->endTime = ($endTime);
                        $track->save();

                    }
                    return response()->json([
                        "data" => "success"]);

                }
                }

            }

            return response()->json([
                "data" => "حدث خطا ما اعد المحاولة لاحقا"]);

        }



}
