<?php


namespace App\MyApplication\Services;


use App\MyApplication\RuleValidate;
use Illuminate\Validation\Rule;

class AdviserRuleValidation extends RuleValidate
{
    public function rules(bool $isrequired = false): array
    {
        $req = $isrequired ? "required" : "nullble";

        return [
            "name" => ["required","string"],
            "about" => [$req,"string"],
            "type" => [$req,"string"],
            "id_user" => ["required","numeric",Rule::exists("users","id")],
            "time"=>["$req","date_format:H:i"],
            "day"=>["required","date"],
            "id_adviser"=>["required","numeric",Rule::exists("advisers","id")],
            "id_date"=>["required","numeric",Rule::exists("dates","id")],



        ];
    }
}



