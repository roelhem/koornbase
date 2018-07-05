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
    public function person($id)
    {
        $this->where('person_id','=',$id);
    }

    /**
     * @param $date
     */
    public function application($date)
    {
        $date = Carbon::parse($date);
        $this->where('application','=', $date);
    }

    /**
     * @param $date
     */
    public function applicationBefore($date)
    {
        $date = Carbon::parse($date);
        $this->where('application','<', $date);
    }

    /**
     * @param $date
     */
    public function applicationAfter($date)
    {
        $date = Carbon::parse($date);
        $this->where('application','>=', $date);
    }

    /**
     * @param $date
     */
    public function start($date)
    {
        $date = Carbon::parse($date);
        $this->where('start','=',$date);
    }

    /**
     * @param $date
     */
    public function startBefore($date)
    {
        $date = Carbon::parse($date);
        $this->where('start','<',$date);
    }

    /**
     * @param $date
     */
    public function startAfter($date)
    {
        $date = Carbon::parse($date);
        $this->where('start','>=',$date);
    }

    /**
     * @param $date
     */
    public function end($date)
    {
        $date = Carbon::parse($date);
        $this->where('end','=',$date);
    }

    /**
     * @param $date
     */
    public function endBefore($date)
    {
        $date = Carbon::parse($date);
        $this->where('end','<',$date);
    }

    /**
     * @param $date
     */
    public function endAfter($date)
    {
        $date = Carbon::parse($date);
        $this->where('end','>=',$date);
    }


}
