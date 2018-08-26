<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 19:11
 */

namespace App\Http\GraphQL\Types\Scalars;


class DateType extends DateTimeType
{

    protected $format = 'Y-m-d';

    protected $attributes = [
        'name' => 'Date'
    ];

}