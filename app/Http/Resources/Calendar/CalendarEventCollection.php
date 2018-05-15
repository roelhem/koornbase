<?php

namespace App\Http\Resources\Calendar;

use App\Helpers\Calendar\CalendarEventHelper;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CalendarEventCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        list($start, $end) = CalendarEventHelper::parseGetRequest($request);

        return [
            'data' => $this->collection,
            'meta' => [
                'search_start' => $start->format('c'),
                'search_end' => $end->format('c'),
                'search_days' => $end->diffInDays($start),
                'total' => $this->collection->count(),
            ],
        ];
    }
}
