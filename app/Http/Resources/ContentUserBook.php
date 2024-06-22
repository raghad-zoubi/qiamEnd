<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentUserBook extends JsonResource
{
    public function toArray($request)
    {
            return [
                "id_content" => $this->id,
                "id_online_center" => $this->id_online_center,
                "name" => $this->name,
                "photo" => $this->photo,
                "numberHours" => $this->numberHours,
                "numberVideos" => $this->numberVideos,
                "exam" => $this->exam,
                "rank" => $this->rank,
                "durationExam" => $this->durationExam,
                "numberQuestion" => $this->numberQuestion,
                "video" => $this->video->map(function ($video) {
                    return [
                        "id_video" => $video->id,
                        "name" => $video->name,
                        "rank" => $video->rank,
                        "poster" => $video->poster,
                    ];
                }),
                "file" => $this->file->map(function ($file) {
                    return [
                        "id_file" => $file->id,
                        "name" => $file->name,
                        "rank" => $file->rank,
//                        "poster" => $file->poster,
                    ];
                }),
            ];

    }
}
