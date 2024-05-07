<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoCourses extends JsonResource
{


    public function toArray(Request $request): array
    {
        $result = [];
        foreach ($this->video as $item) {
            $data = [
                'id' => $item['id']??null,
                'id_content' => $item['id_content']??null,
                'name' => $item['name']??null,
                'duration' => $item['duration']??null,
                'video' => $item['video']??null,
                'rank' => $item['rank']??null,

            ];

            $result[] = $data;
        }




        return $result;
    }
}
