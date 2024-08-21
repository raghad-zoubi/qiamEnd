<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsOnlineCourses extends JsonResource
{


    public function toArray(Request $request): array
    {
        $content = [];

        foreach ($this->content as $item) {
            $data = [
                'id' => $item['id'],
                'id_online_center' => $item['id_online_center'],
                'numberHours' => $item['numberHours'],
                'numberVideos' => $item['numberVideos'],
                'name' => $item['name'],
                'photo' => $item['photo'],
                'rank' => $item['rank'],
                'exam' => $item['exam'],

            ];

            $content[] = $data;
        }


        $result = [
            'id' => $this->id??null,
            'favorite' => $this->fav??null,
            'book' => $this->booked??null,
            'can' => $this->can??null,
            'done' => $this->done??null,
            'rate' => $this->avg_rate??null,
            'name' => $this->course->name??null,
            'photo' => $this->course->photo??null,
            'about' => $this->course->about??null,
            'price' => $this->online->price??null,
            'numberVideos' => $this->online->numberVideos??null,
            'numberHours' => $this->online->numberHours??null,
            'serial' => $this->online->serial??null,
            'isopen' => $this->online->isopen??null,
            'exam' => $this->online->exam??null,
            'content' => $content??null
        ];

        return $result;
    }
}
