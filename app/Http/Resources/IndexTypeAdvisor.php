<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexTypeAdvisor extends JsonResource
{


    public function toArray(Request $request): array
    {
        $result = [
            'id' => $this->id ??null,
            'id_user' => $this->id_user ??null,
            'photo' => $this->photo ??null,
            'type' => $this->type ??null,
                "about"=>  $this->about ??null,
                    'name' => $this->name??null,

        ];


        return $result;
    }
}
