<?php

namespace App\MyApplication\Services;



use App\MyApplication\RuleValidate;
use Illuminate\Validation\Rule;

class FileRuleValidation extends RuleValidate
{
    public function rules(bool $isrequired = false): array
    {
        $req = $isrequired ? "required" : "nullble";
        return [
            "name" => [$req,"string"],//Rule::unique("cource","name")],
            "path" => [$req,"path"],
            "about" => [$req,"about"],
//            "id_group" => ["required","numeric",Rule::exists("groups","id")],
            "id" => ["required","numeric",Rule::exists("courses","id")],
            "ids_user" => ["array"],
//            "ids_user.*" => ["numeric",Rule::exists("users","id")]
        ];
    }
}
