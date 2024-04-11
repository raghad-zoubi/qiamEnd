<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileCourses extends JsonResource
{


    public function toArray(Request $request): array
    {

        $result = [];
        foreach ($this->file as $item) {
            $data = [
                'id' => $item['id']??null,
                'id_content' => $item['id_content']??null,
                'name' => $item['name']??null,
                'file' => $item['file']??null,
                'rank' => $item['rank']??null,
            ];

            $result[] = $data;
        }




        return $result;
    }
}
