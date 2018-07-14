<?php

namespace App\ModelFilters;

class UserFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [
        'person' => ['membership_status']
    ];


    public function person($id)
    {
        $this->where('person_id',$id);
    }

}
