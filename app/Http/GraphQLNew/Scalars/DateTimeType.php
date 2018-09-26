<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-09-18
 * Time: 21:28
 */

namespace App\Http\GraphQLNew\Scalars;


use App\Services\Parsers\NotParsableException;
use GraphQL\Error\Error;


use GraphQL\Type\Definition\ScalarType;


class DateTimeType extends ScalarType
{

    public $name = 'DateTime';

    public $description = 'The `DateTime` scalar type represents certain moment in time. It\'s `string`-value is formatted like "`yyyy-mm-dd HH:mm:ss`",';

    /**
     * Returns the format in which the DateTime-object should be outputted in the response.
     *
     * @return string
     */
    protected function getFormat() {
        return config('graphql.output_formats.datetime');
    }

    /** @inheritdoc */
    public function parseLiteral($valueNode)
    {
        try {
            return \Parse::date($valueNode);
        } catch (NotParsableException $exception) {
            throw new Error(
                "Can't parse valueNode of type: {$valueNode->kind} to a Carbon instance.",
                [$valueNode], null, null, $exception
            );
        }
    }

    /** @inheritdoc */
    public function parseValue($value)
    {
        try {
            return \Parse::date($value);
        } catch (NotParsableException $exception) {
            throw new Error("Can't parse the value: '{$value}' to a Carbon instance.");
        }
    }


    /**
     * Serializes an internal value to include in a response.
     *
     * @param mixed $value
     * @return mixed
     */
    public function serialize($value)
    {
        return \Parse::date($value)->format($this->getFormat());
    }
}