<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-07-18
 * Time: 16:34
 */

namespace App\Services\Filters;


use App\Exceptions\Filters\FilterInvalidParametersException;
use App\Services\Filters\Traits\HasOwnerFilter;
use Illuminate\Database\Eloquent\Builder;

class PersonFilterProvider extends AbstractFilterProvider
{

    use HasOwnerFilter;

    /**
     * A filter that only passes Persons that have a birth date before the provided parameter.
     *
     * @param mixed $params
     * @return \Closure
     * @throws FilterInvalidParametersException
     */
    function filterBirthDateBefore($params) {
        $date = $this->parseDateParam($params);

        return function(Builder $query) use ($date) {
            return $query->where('birth_date', '<=', $date);
        };
    }

    /**
     * A filter that only passes Persons that have a birth date after the provided parameter.
     *
     * @param mixed $params
     * @return \Closure
     * @throws FilterInvalidParametersException
     */
    function filterBirthDateAfter($params) {
        $date = $this->parseDateParam($params);

        return function(Builder $query) use ($date) {
            return $query->where('birth_date', '>=', $date);
        };
    }

    /**
     * A filter that only passes Persons that have a birth date that is exactly the provided date.
     *
     * @param mixed $params
     * @return \Closure
     * @throws FilterInvalidParametersException
     */
    function filterBirthDate($params) {
        $date = $this->parseDateParam($params);

        return function (Builder $query) use ($date) {
            return $query->where('birth_date', '=', $date);
        };
    }

    /**
     * A filter that only passes Persons that have one of the provided membership statuses.
     *
     * @param mixed $params
     * @return \Closure
     */
    function filterMembershipStatus($params) {
        return function (Builder $query) use ($params) {
            return $query->membershipStatus($params);
        };
    }

}