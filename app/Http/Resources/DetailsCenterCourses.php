<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsCenterCourses extends JsonResource
{


    public function toArray(Request $request): array
    {


        $result = [

            'id' => $this->id??null,
            'rate' => $this->avg_rate??null,
            'id_course' => $this->course->id??null,
            'name' => $this->course->name??null,
            'photo' => $this->course->photo??null,
            'about' => $this->course->about??null,
            'id_center'=>  $this->center->id??null,
                'start'=>  $this->center->start??null,
                'end'=> $this->center->end??null,
                'numberHours'=>  $this->center->numberHours??null,
                'numberContents'=> $this->center->numberContents??null,
                'price'=>  $this->center->price??null



];


        return $result;
    }
}
