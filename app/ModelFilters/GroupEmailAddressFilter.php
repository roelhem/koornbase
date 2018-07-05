<?php

namespace App\ModelFilters;

use App\ModelFilters\Traits\IsPersonContactEntry;

class GroupEmailAddressFilter extends ModelFilter
{

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


    public function group($id)
    {
        $this->where('group_id','=',$id);
    }
}
