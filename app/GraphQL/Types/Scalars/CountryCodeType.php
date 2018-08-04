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
        if(!is_string($value) || strlen($value) !== 2) {
            return null;
        }

        return mb_strtoupper($value);
    }

    public function parseValue($value)
    {
        if(is_string($value) && strlen($value) === 2) {
            return mb_strtoupper($value);
        } else {
            return null;
        }
    }

    public function parseLiteral($valueNode)
    {
        if(($valueNode instanceof StringValueNode) && strlen($valueNode->value) === 2) {
            return mb_strtoupper($valueNode->value);
        }

        throw new \InvalidArgumentException("A CountryCode has to be a string with just two characters", [$valueNode]);
    }

}