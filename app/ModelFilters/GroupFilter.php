<?php

namespace App\ModelFilters;

class GroupFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Filters all the groups that belong to a certain group category.
     *
     * @param $id
     */
    public function category($id)
    {
        $this->where('id','=',$id);
    }
}
