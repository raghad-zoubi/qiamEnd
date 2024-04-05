<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use App\Models\Bouns;
use App\Models\Child;
use App\Models\User;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommonCourses extends JsonResource
{


    public function toArray(Request $request): array
    {
                $result = [
                    'id' => $this->id,
                    'rate' => $this->avg_rate,
                    'name' => $this->course->name,
                    'photo' => $this->course->photo,
                    'type' => null,
                ];
                if ($this->online !== null) {
                    // Read the data when it is not null
                    $result['price'] = $this->online->price;
                    $result['numberHours'] = $this->online->numberHours;
                    $result['numberVideos'] = $this->online->numberVideos;
                    $result['isopen'] = $this->online->isopen;
                    $result['type'] = 'online';

                }
                if ($this->center !== null) {
                    // Read the data when it is not null
                    $result['price'] = $this->center->price;
                    $result['numberHours'] = $this->center->numberHours;
                    $result['numberContents'] = $this->center->numberContents;
                    $result['type'] = 'center';


                }


        return $result;
    }
}
