<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-05-18
 * Time: 09:51
 */

namespace App\Helpers\Calendar;


use Carbon\Carbon;
use Illuminate\Http\Request;

final class CalendarEventHelper
{

    /**
     * This function parses a typical CalendarEvent get-request and returns the $start and $end Carbon instances.
     *
     * @param Request $request
     * @param integer $default_days
     * @return Carbon[]
     */
    public static function parseGetRequest(Request $request, $default_days = 365) {

        // Finding the parameters
        $start_input = $request->get('start', null);
        $end_input = $request->get('end', null);
        $days_input = $request->get('days', $default_days);

        // Parsing the input
        $days = abs(intval($days_input));
        $start = null;
        $end = null;
        if($start_input !== null) {
            $start = Carbon::parse($start_input);
        }
        if($end_input !== null) {
            $end = Carbon::parse($end_input);
        }

        // Determine the start time, if it is not yet determined
        if(!($start instanceof Carbon)) {
            if($end instanceof Carbon) {
                $start = clone $end;
                $start->subDays($days);
            } else {
                $start = Carbon::now();
            }
        }

        // Determine the end time, if it is not yet determined
        if(!($end instanceof Carbon)) {
            $end = clone $start;
            $end->addDays($days);
        }

        // Check if $end is after $start.
        if($end < $start) {
            abort( 400, "The 'end'-attribute must describe a later moment then the 'start'-attribute.");
        }

        // Return the result
        return [$start, $end];

    }

}