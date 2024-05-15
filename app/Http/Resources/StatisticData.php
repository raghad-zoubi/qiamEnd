<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class StatisticData extends JsonResource
{


public static


    function mergeAndTransformData(Collection $bookingsByMonth, Collection $certificatesByMonth): array
    {
        // Merge bookingsByMonth and certificatesByMonth arrays
        $mergedData = array_merge($bookingsByMonth->toArray(), $certificatesByMonth->toArray());
        // Group merged data by year and month
        $groupedData = collect($mergedData)->groupBy(['year', 'month']);
        // Transform grouped data
        $transformedData = $groupedData->map(function ($items) {
            $countBooking = 0;
            $countCertificates = 0;

            if ($items->isNotEmpty()) {
                foreach ($items as $item) {
                    if (isset($item['countb'])) {
                        dd($item['year']);
                        $countBooking += $item['countb'];
                    }
                    if (isset($item['countc'])) {
                        $countCertificates += $item['countc'];
                    }
                }
                return [
                    'year' => $items['year'],
                    'month' => $items['month'],
                    'countBooking' => $countBooking,
                    'countCertificates' => $countCertificates
                ];    dd("f");
            } else {
                return null; // Return null for empty groups
            }
        });

        // Filter out null values
        $transformedData = $transformedData->filter();

        // Reindex transformed data
        $transformedData = $transformedData->values()->all();

        // Format final result
        return ['ByMonth' => $transformedData];
    }
}












