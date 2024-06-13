<?php

namespace App\MyApplication\Services;

use App\MyApplication\RuleValidate;
use Illuminate\Validation\Rule;

/**
 * @method onlyKey(string[] $array, bool $true)
 */
class PaperRuleValidation extends  RuleValidate
{
    public function rules(bool $isrequired = false): array
    {
        $req = $isrequired ? "required" : "nullble";
        return [
            "address" => [$req,"string"],
            "name" => [$req,"string"],
        //    "kind" => [$req,"numeric",Rule::in(["0","1"])],
          //  "id_group" => ["required","numeric",],
        ];
    }
}
