<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-07-18
 * Time: 00:08
 */

namespace App\GraphQL\Types\Scalars;


use GraphQL\Language\AST\StringValueNode;

class CountryCodeType extends ScalarType
{

    protected $attributes = [
        'name' => 'CountryCode'
    ];

    public function serialize($value)
    {
        return \Parse::countryCode($value);
    }

    public function parseValue($value)
    {
        return \Parse::countryCode($value, true);
    }

    public function parseLiteral($valueNode)
    {
        if(($valueNode instanceof StringValueNode)) {
            return \Parse::countryCode($valueNode->value, true);
        }

        throw new \InvalidArgumentException("A CountryCode has to be a string", [$valueNode]);
    }

}