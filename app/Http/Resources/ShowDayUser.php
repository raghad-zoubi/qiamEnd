<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function Nette\Utils\isEmpty;

class ShowDayUser extends JsonResource
{
    public function toArray(Request $request): array
    {
        $result = [];



        foreach ($this as $item) {
            if ($item != null)
                if (($item['reserve'])=="[]"&&$item!=null)
                     $result = [
               'id_data' => $item['id'] ?? null,
               'id_adviser' => $item['id_adviser'] ?? null,
               'from' => $item['from'] ?? null,
               'to' => $item['to'] ?? null,
               'day' => $item['day'] ?? null,];}









        return $result;
    }

}
