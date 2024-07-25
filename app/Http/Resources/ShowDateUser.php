<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function Nette\Utils\isEmpty;
use function Ramsey\Collection\addFirst;
use function Ramsey\Collection\lastElement;
use function Ramsey\Collection\removeFirst;
use function SebastianBergmann\RecursionContext\addArray;

class ShowDateUser extends JsonResource
{public function toArray(Request $request): array
{
    $result = [];

    foreach ($this as $item) {
        if ($item != null && !isset($item['reserve'][0]['id'])) {
            $result[] = [
                'id_data' => $item['id'] ?? null,
                'id_adviser' => $item['id_adviser'] ?? null,
                'from' => $item['from'] ?? null,
                'to' => $item['to'] ?? null,
                'day' => $item['day'] ?? null,
            ];
        }
    }

    // Filter out any empty arrays from $result
    $result = array_filter($result, function($item) {
        return !empty($item);
    });

    // Wrap the result in "DateGet" key
    return $result;
}

}
