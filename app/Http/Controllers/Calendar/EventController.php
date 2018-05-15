<?php

namespace App\Http\Controllers\Calendar;

use App\Helpers\Calendar\CalendarEventHelper;
use App\Http\Resources\Calendar\EventResource;
use App\Http\Resources\Calendar\CalendarEventCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Event;

class EventController extends Controller
{

    public function list(Request $request) {
        list($start, $end) = CalendarEventHelper::parseGetRequest($request);

        $query = Event::query()->where('end','>=',$start)->where('start','<=',$end)
                ->orderBy('start');

        return new CalendarEventCollection(EventResource::collection($query->get()));
    }

}
