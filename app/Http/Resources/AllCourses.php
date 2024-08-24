<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use App\Models\Bouns;
use App\Models\Child;
use App\Models\User;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllCourses extends JsonResource
{


    public function toArray(Request $request): array
    {

        $result = [
            'id_online_center' => $this->id??null,
            'rate' => $this->avg_rate??null,
            'name' => $this->course->name??null,
            'photo' => $this->course->photo??null,
            'type' => null
        ];

        if ($this->online !== null) {
            // Read the data when it is not null
            $result['price'] = $this->online->price??null;
            $result['numberHours'] = $this->online->numberHours??null;
            $result['numbervideo'] = $this->online->numberVideos??null;
            $result['type'] = 'online';

        }
        if ($this->center !== null) {
            // Read the data when it is not null
            $result['price'] = $this->center->price??null;
            $result['numberHours'] = $this->center->numberHours??null;
            $result['numbervideo'] = $this->center->numberContents??null;
            $result['type'] = 'center';


        }


        return $result;
    }
}
