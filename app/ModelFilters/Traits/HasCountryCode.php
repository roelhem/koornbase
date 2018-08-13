<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 05-07-18
 * Time: 10:24
 */

namespace App\ModelFilters\Traits;


trait HasCountryCode
{

    /**
     * Filters only the models with a certain country code.
     *
     * @param $code
     */
    public function countryCode($code)
    {
        $this->where('country_code',$code);
    }

}