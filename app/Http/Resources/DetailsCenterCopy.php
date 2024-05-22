<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use App\Models\Bouns;
use App\Models\Child;
use App\Models\Reserve;
use App\Models\User;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsCenterCopy extends JsonResource
{


    public function toArray(Request $request): array
    {

            $result = ['start' => $this->center->start,
        'end' => $this->center->end,
        'numberHours' => $this->center->numberHours,
        'numberContents' => $this->center->numberContents,
        'price' => $this->center->price,
            ];


        return $result;
    }
}
