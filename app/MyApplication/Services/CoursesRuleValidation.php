<?php


namespace App\MyApplication\Services;

use App\MyApplication\RuleValidate;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoursesRuleValidation extends RuleValidate
{
    public function rules(bool $isrequired = false): array
    {
        $req = $isrequired ? "required" : "nullble";
        return [
            "name" => ["required","string"],//Rule::unique("cource","name")],
          //  "photo" => ["required","file"],
            "about" => ["required","string"],
            //----
            "start"=>[$req,"date"],
            "end"=>[$req,"date"],
            "numberHours"=>[$req,"numeric"],
            "numberLectures"=> [$req,"numeric"],
            "id_course"=>["required","numeric",Rule::exists("courses","id")],
            "id_form"=>["required","numeric",Rule::exists("forms","id")],
            "id_poll"=>["required","numeric",Rule::exists("polls","id")],
            //------
            "Exam" => ["required","numeric",Rule::in(["0","1"])],
            "serial" => ["required","numeric",Rule::in(["0","1"])],
            "durationExam" => ["required","date_format:H:i"],
            "amount" => ["required","numeric"],
        ];
    }
}
