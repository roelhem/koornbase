<?php

namespace App\ModelFilters;

use App\ModelFilters\Traits\IsPersonContactEntry;

class PersonEmailAddressFilter extends ModelFilter
{

    use IsPersonContactEntry;

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];
}
