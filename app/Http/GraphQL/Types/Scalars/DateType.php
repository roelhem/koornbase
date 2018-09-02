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

    protected $attributes = [
        'name' => 'Date',
        'description' => 'The `Date` scalar type represents a specific day (date). It\'s JSON-value is a `string` formatted like "`yyyy-mm-dd`".'
    ];

    /** @inheritdoc */
    protected function getFormat()
    {
        return config('graphql.output_formats.date');
    }

}