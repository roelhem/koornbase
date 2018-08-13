<?php

namespace App\ModelFilters;

class GroupCategoryFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


    public function style($style)
    {
        $this->where('style','=',$style);
    }
}
