<?php

namespace App\ModelFilters;

use App\ModelFilters\Traits\IsOwnedByPerson;
use Carbon\Carbon;

class MembershipFilter extends ModelFilter
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
     * @param $id
     */
    public function personId($id)
    {
        $this->where('person_id','=',$id);
    }

    /**
     * @param $date
     */
    public function application($date)
    {
        $this->where('application','=', \Parse::date($date, true));
    }

    /**
     * @param $date
     */
    public function applicationBefore($date)
    {
        $this->where('application','<', \Parse::date($date, true));
    }

    /**
     * @param $date
     */
    public function applicationAfter($date)
    {
        $this->where('application','>=', \Parse::date($date, true));
    }

    /**
     * @param $date
     */
    public function start($date)
    {
        $this->where('start','=',\Parse::date($date, true));
    }

    /**
     * @param $date
     */
    public function startBefore($date)
    {
        $this->where('start','<',\Parse::date($date, true));
    }

    /**
     * @param $date
     */
    public function startAfter($date)
    {
        $this->where('start','>=',\Parse::date($date, true));
    }

    /**
     * @param $date
     */
    public function end($date)
    {
        $this->where('end','=',\Parse::date($date, true));
    }

    /**
     * @param $date
     */
    public function endBefore($date)
    {
        $this->where('end','<',\Parse::date($date, true));
    }

    /**
     * @param $date
     */
    public function endAfter($date)
    {
        $this->where('end','>=',\Parse::date($date, true));
    }


    public function status($membershipStatus)
    {
        $this->query->status($membershipStatus);
    }

    public function outsiderAt($date)
    {
        $this->query->outsider($date);
    }

    public function noviceAt($date)
    {
        $this->query->novice($date);
    }

    public function memberAt($date)
    {
        $this->query->member($date);
    }

    public function formerMemberAt($date)
    {
        $this->query->formerMember($date);
    }


}
