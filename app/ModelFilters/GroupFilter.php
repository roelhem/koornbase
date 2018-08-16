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
    public function categoryId($id)
    {
        $this->where('category_id','=',$id);
    }

    /**
     * Filters the groups that belong to a category in the provided list.
     *
     * @param $ids
     */
    public function anyCategoryId($ids)
    {
        $this->whereIn('category_id', $ids);
    }
}
