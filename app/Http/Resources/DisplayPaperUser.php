<?php

namespace App\Http\Resources;

use App\Models\User;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;





class DisplayPaperUser extends JsonResource{
    public function toArray(Request $request): array
    {

        $result =  [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'name' => $this->name,
            'lastName' => $this->lastName,
            'mobilePhone' => $this->mobilePhone,
            'type_question' => $this->select,
            'question' => $this->question,
            'answer' => $this->answer,
            'id_question' => $this->id_question,
            'id_answer' => $this->id_answer,
            'id_user' => $this->id_user,
        ];
        return $result ;
    }


}
