<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function Nette\Utils\isEmpty;

class ShowDay extends JsonResource
{


    public function toArray(Request $request): array
    {  $result = [];
   // dd($this['id']);
        $reserve = [];

//        foreach ($this as $item) {
//            if (!empty($item )) {
//                foreach ($item['reserve'] as $re) {
//
//                    //   if (!$item->isEmpty()) {
//                if(!empty($re)){
//
//                    $data = [
//                    'id' => $item['id'],
//                    'id_user' => $item['id_user'],
//                    'status' => $item['status'],
//                ];
//
//            }
//
//              if(empty($item)){
//
//                $data = [
//                    'status' => 'ليست محجوزة',
//                ];
//
//            }
//                    $reserve[] = $data;
//
//                }
//            }
//        }
        foreach ($this as $item) {
            if ($item != null) {
                if ($item['reserve'] != null)
                {
                    foreach ($item['reserve'] as $re) {
                        //   if (!$item->isEmpty()) {
                        if (!empty($re)) {

                            $da = [
                                'id' => $re['id'],
                                'id_user' => $re['id_user'],
                                'status' => $re['status'],
                                'name' => $re['users2']['profile']['name'],
                                'lastName' => $re['users2']['profile']['lastName'],
                                'fatherName' => $re['users2']['profile']['fatherName'],
                            ];

                        }
                        else if (empty($re)) {

                            $da = [
                                'status' => 'ليست محجوزة',
                            ];

                        }
                        $reserve[] = $da;

                    }
                }
                $data = [
                    'id' => $item['id'] ?? null,
                   // 'id_adviser' => $item['id_adviser'] ?? null,
                    'from' => $item['from'] ?? null,
                    'to' => $item['to'] ?? null,
                //    'day' => $item['day'] ?? null,
                    'reserve' => $reserve ?? null,


                ];

                $result[] = $data;

            }

        }



        return $result;
    }
    }
