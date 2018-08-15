<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 13:58
 */

namespace App\Services\Parsers;


use Carbon\Carbon;

class ParseService
{

    /**
     * Creates an ParseWithAlternative instance. This will also parse the input, but will return a default value
     * instead of throwing an error on failure.
     *
     * @param null $default
     * @return ParseWithAlternative
     */
    public function try($default = null) {
        return new ParseWithAlternative($this, $default);
    }


    /**
     * Function that tries to return a Carbon instance.
     *
     * @param mixed $input
     * @param boolean $nowOnNull
     * @return Carbon|null
     */
    public function date($input, $nowOnNull = true)
    {
        if($input instanceof Carbon) {
            return $input;
        }

        if($input instanceof \DateTime) {
            return Carbon::instance($input);
        }

        if(is_string($input)) {
            return Carbon::parse($input);
        }

        if(is_integer($input)) {
            return Carbon::createFromTimestamp($input);
        }

        if(is_float($input)) {
            return Carbon::createFromTimestampMs(round($input * 100));
        }

        if($nowOnNull && $input === null) {
            return Carbon::now();
        }

        throw new NotParsableException('date', $input);
    }

}