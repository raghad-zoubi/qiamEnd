<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function Nette\Utils\isEmpty;

class ShowAdviser extends JsonResource
{


    public function toArray(Request $request): array
    {
        $result = [];
        $date = [];
        $reserve = [];


        if ($this != null) {
                if (!$this['date']->isEmpty()) {

                foreach ($this['date'] as $item) {

                    if ($item['reserve'] != null)
                        foreach ($item['reserve'] as $re) {
                            //
                            if (!empty($re)) {
                                $re = [
                                    'id' => $re['id'],
                                  //  'id_user' => $re['id_user'],
                                    'status' => $re['status'],
                                    'name' => $re['users2']['profile']['name'],
                                    'lastName' => $re['users2']['profile']['lastName'],
                                    'fatherName' => $re['users2']['profile']['fatherName'],
                                    'mobilePhone' => $re['users2']['profile']['mobilePhone'],
                                ];

                            } else if (empty($re)) {

                                $re = [
                                    'status' => 'ليست محجوزة',
                                ];

                            }
                            $reserve[] = $re;

                        }


                    $da = [
                        //'id_date' => $item['id'] ?? null,
                        //'id_adviser' => $item['id_adviser'] ?? null,
                      //  'from' => $item['from'] ?? null,
                       // 'to' => $item['to'] ?? null,
                        'day' => $item['day'] ?? null,
                        'id' => $item['id'] ?? null,
                    //   'reserve' => $reserve ?? null,
                    ];

                    $date[] = $da;


                }
            }
            $adv = [
                'name' => $this['name'] ?? null,
                'about' => $this['about'] ?? null,
                'photo' => $this['photo'] ?? null,
                'type' => $this['type'] ?? null,

               'date' => $date ?? null,


            ];
       // $result[] = $adv;
        return $adv;
    }

        }

}
