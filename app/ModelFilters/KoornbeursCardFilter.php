<?php

namespace App\ModelFilters;

use App\ModelFilters\Traits\IsOwnedByPerson;

class KoornbeursCardFilter extends ModelFilter
{

    use IsOwnedByPerson;

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];



    public function version($version)
    {
        $this->where('version','=',$version);
    }

    public function active($state)
    {
        if($state) {
            $this->query->active();
        } else {
            $this->query->inactive();
        }
    }

    public function activeAt($timestamp)
    {
        $this->query->active($timestamp);
    }

    public function inactiveAt($timestamp)
    {
        $this->query->inactive($timestamp);
    }

    public function activatedBefore($timestamp)
    {
        $this->query->where('activated','<',$timestamp);
    }

    public function activatedAfter($timestamp)
    {
        $this->query->where('activated','>=', $timestamp);
    }

    public function deactivatedBefore($timestamp)
    {
        $this->query->where('deactivated','<', $timestamp);
    }

    public function deactivatedAfter($timestamp)
    {
        $this->query->where('deactivated','>=', $timestamp);
    }

}
