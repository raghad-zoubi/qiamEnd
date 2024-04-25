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

class StatisticAdvisor extends JsonResource
{


    public function toArray(Request $request): array
    {

            $result = [
                'id_reserve' => $this->id??null,
                'id_user' => $this->id_user??null,
                'id_date' => $this->id_date??null,
                'id_profile' => $this->users2->profile->id??null,
                'id_adviser' => $this->reserve2->adviser->id??null,
                'name_user' => $this->users2->profile->name??null,
                'lastName' => $this->users2->profile->lastName??null,
                'fatherName' => $this->users2->profile->fatherName??null,
                'from' => $this->reserve2->from??null,
                'to' => $this->reserve2->to??null,
                'name_adviser' => $this->reserve2->adviser->name??null,
                'type' => $this->reserve2->adviser->type??null,
            ];


        return $result;
    }
}
