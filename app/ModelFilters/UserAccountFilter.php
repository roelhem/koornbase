<?php

namespace App\ModelFilters;

class UserAccountFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


    public function userId($id)
    {
        return $this->where('user_id',$id);
    }

    public function provider($provider)
    {
        return $this->where('provider', $provider);
    }
}
