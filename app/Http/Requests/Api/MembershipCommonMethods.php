<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 12:43
 */

namespace App\Http\Requests\Api;


use App\Membership;
use Carbon\Carbon;
use Illuminate\Validation\Validator;

trait MembershipCommonMethods
{

    /**
     * The way a date should be parsed to use in the after-validation.
     *
     * @param $input
     * @return Carbon|null
     */
    protected function parseDate($input) {
        if($input instanceof Carbon) {
            return $input;
        } elseif(is_string($input)) {
            return Carbon::parse($input);
        } else {
            return null;
        }
    }

    /**
     * @param Carbon|null $application
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @param Validator $validator
     */
    protected function validateChronology($application, $start, $end, $validator) {
        if($application !== null) {
            if ($start !== null && $application > $start) {
                $validator->errors()->add('start', 'De start-datum moet na de inschrijvingsdatum zijn.');
            }
            if ($end !== null && $application > $end) {
                $validator->errors()->add('end', 'De eind-datum moet na de inschrijvingsdatum zijn.');
            }
        }
        if($start !== null && $end !== null && $start > $end) {
            $validator->errors()->add('end', 'De eind-datum moet na de start-datum zijn.');
        }
    }

    /**
     * Returns the lowest date of the three membership dates.
     *
     * @param Carbon|null $application
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @return Carbon|null
     */
    protected function findLowerBound($application, $start, $end) {
        return $application ?? $start ?? $end;
    }

    /**
     * Returns the attribute name of the first membership date that is not null.
     *
     * @param Carbon|null $application
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @return string|null
     */
    protected function findLowerBoundAttribute($application, $start, $end) {
        if($application !== null) { return 'application'; }
        if($start !== null)       { return 'start'; }
        if($end !== null)         { return 'end'; }
        return null;
    }

    /**
     * Returns whether or not the given upper and lower bounds overlap each other
     *
     *
     * @param Carbon|null $lower
     * @param Carbon|null $upper
     * @param Carbon|null $otherLower
     * @param Carbon|null $otherUpper
     * @return bool
     */
    protected function datesDistinct($lower, $upper, $otherLower, $otherUpper) {
        $belowOther = ($upper !== null && $otherLower !== null && $upper < $otherLower);
        $aboveOther = ($lower !== null && $otherUpper !== null && $lower > $otherUpper);
        return $belowOther || $aboveOther;
    }

}