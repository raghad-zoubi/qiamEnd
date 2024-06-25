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
    {  $poll=null;
        $form=null;
        foreach ($this->coursepaper as $p){
        if($p->paper->type=='استمارة')
        {$poll=$p->paper->title;}
        else if($p->paper->type=='استبيان')
        {$form=$p->paper->title;}

    }

            $result = [
                'form' => $form,
                'poll' => $poll,
                'start' => $this->center->start,
        'end' => $this->center->end,
        'numberHours' => $this->center->numberHours,
        'numberContents' => $this->center->numberContents,
        'price' => $this->center->price,
            ];


        return $result;
    }
}
