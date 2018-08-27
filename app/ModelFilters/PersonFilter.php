<?php

namespace App\ModelFilters;

use App\Enums\MembershipStatus;
use App\ModelFilters\Traits\IsOwnedByPerson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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
        $this->where('birth_date','<=',\Parse::date($date, true));
    }

    /**
     * Filter that only returns the persons which have a birth date after the provided date.
     *
     * @param string|null $date
     */
    public function birthDateAfter($date)
    {
        $this->where('birth_date','>=', \Parse::date($date, true));
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

    /**
     * Filter that only returns the persons that are in at least one of the provided groups.
     *
     * @param array $groups
     */
    public function inAnyGroup($groups) {
        $this->query->whereHas('groups', function($query) use ($groups) {
            /** @var Builder $query */
            $query->whereIn('id',$groups);
        });
    }

    /**
     * Filter that only returns the persons that are not in the provided group.
     *
     * @param $groupId
     */
    public function notInGroup($groupId) {
        $this->query->whereDoesntHave('groups',function($query) use ($groupId) {
            /** @var Builder $query */
            $query->where('id',$groupId);
        });
    }


}
