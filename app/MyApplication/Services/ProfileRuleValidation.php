<?php


namespace App\MyApplication\Services;


use App\MyApplication\RuleValidate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileRuleValidation extends RuleValidate
{
    public function rules(bool $isrequired = false): array
    {
        $req = $isrequired ? "required" : "nullble";

        return [
            "name" => [$req,"string"],
            "lastName" => [$req,"string"],
            "fatherName" => [$req,"string"],
            "specialization" => [$req,"string"],
            "gender" => [$req,"string",Rule::in(["m","f"])],
            "mobilePhone" => [$req,"numeric",Rule::unique("profiles","mobilePhone")],
            "birthDate" => [$req,"date"],
          //  "id_user" => ["required","numeric",Rule::exists("users","id")],
        ];
    }
}
