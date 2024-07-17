<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Present extends JsonResource
{
//{
//    ,
//                "reserve2": {
//    "id": 41,
//                    "id_adviser": 68,
//                    "from": "20:09:00",
//                    "to": "09:09:00",
//                    "day": "2024-05-07",
//                    "adviser": {
//        "id": 68,
//                        "id_user": null,
//                        "name": "kkkيسس",
//                        "type": "",
//                        "photo": "file/LbNJ5WoJqRJKjrtzh8AYh4V50BHj4tMLkV1WNlck.png",
//                        "about": "نفسية"
//                    }
//                }
//            },
//

    public function toArray(Request $request): array
    {

        $result = [
            'id' => $this->reserve2->id??null,
            'id_adviser' => $this->reserve2->id_adviser??null,
            'name' => $this->reserve2->adviser->name??null,
            'about' => $this->reserve2->adviser->about??null,
            'photo' => $this->reserve2->adviser->photo??null,
            'type' => $this->reserve2->adviser->type??null,
            'day' => $this->reserve2->day??null,
            'from' => $this->reserve2->from??null,
            'to' => $this->reserve2->to??null,
            'status' => $this->status??null,
            ];
//            'rate' => $this->avg_rate??null,
//            'name' => $this->course->name??null,
//            'photo' => $this->course->photo??null,
//            'type' => null
//        ];
//
//        if ($this->online !== null) {
//            // Read the data when it is not null
//            $result['price'] = $this->online->price??null;
//            $result['type'] = 'online';
//
//        }
//        if ($this->center !== null) {
//            // Read the data when it is not null
//            $result['price'] = $this->center->price??null;
//            $result['type'] = 'center';
//
//
//        }


        return $result;
    }
}
