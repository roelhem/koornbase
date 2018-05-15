<?php

namespace App\Http\Controllers\Calendar;

use App\Helpers\Calendar\CalendarEventHelper;
use App\Http\Resources\Calendar\BirthDayResource;
use App\Http\Resources\Calendar\CalendarEventCollection;
use App\Person;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{

    public function list(Request $request) {
        list($start, $end) = CalendarEventHelper::parseGetRequest($request);

        $query = Person::query()->member()
            ->whereRaw("birth_date + date_trunc('year', age( ? , birth_date)) + interval '1 year' <= ?",[$start, $end])
            ->orderByRaw("birth_date + date_trunc('year', age(birth_date))");

        return new CalendarEventCollection(BirthDayResource::collection($query->get()));
    }

}
