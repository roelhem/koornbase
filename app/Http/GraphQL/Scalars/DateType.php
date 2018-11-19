<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 10:20
 */

namespace App\Http\GraphQL\Scalars;


class DateType extends DateTimeType
{

    public $name = 'Date';

    public $description = 'The `Date` scalar type represents a specific day (date). It\'s `string`-value is formatted like "`yyyy-mm-dd`",';

    /**
     * Returns the format in which the DateTime-object should be outputted in the response.
     *
     * @return string
     */
    protected function getFormat() {
        return config('graphql.output_formats.date');
    }

}