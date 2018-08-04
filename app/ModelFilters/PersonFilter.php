<?php

namespace App\ModelFilters;

use App\Enums\MembershipStatus;
use App\ModelFilters\Traits\IsOwnedByPerson;
use Carbon\Carbon;

class PersonFilter extends ModelFilter
{

    use IsOwnedByPerson;

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


    /**
     * Filter that only returns the persons which have a birth date before the provided date.
     *
     * @param string|null $date
     */
    public function birthDateBefore($date)
    {
        $this->where('birth_date','<=',Carbon::parse($date));
    }

    /**
     * Filter that only returns the persons which have a birth date after the provided date.
     *
     * @param string|null $date
     */
    public function birthDateAfter($date)
    {
        $this->where('birth_date','>=', Carbon::parse($date));
    }

    /**
     * Filter that only returns the persons which have a specific membership_status
     *
     * @param string|MembershipStatus $status
     */
    public function membershipStatus($status)
    {
        $this->query->membershipStatus($status);
    }

    /**
     * Filter that only returns the persons which have one of the status in the provides list.
     *
     * @param array $statusList
     */
    public function anyMembershipStatus($statusList)
    {
        $this->query->membershipStatus($statusList);
    }


}
