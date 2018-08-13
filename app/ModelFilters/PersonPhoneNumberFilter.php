<?php

namespace App\ModelFilters;

use App\ModelFilters\Traits\HasCountryCode;
use App\ModelFilters\Traits\IsPersonContactEntry;

class PersonPhoneNumberFilter extends ModelFilter
{
    use IsPersonContactEntry;
    use HasCountryCode;

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [
        'person' => ['membership_status']
    ];



}
