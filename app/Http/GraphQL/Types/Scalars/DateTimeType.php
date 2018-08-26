<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 18:04
 */

namespace App\Http\GraphQL\Types\Scalars;


use Carbon\Carbon;
use GraphQL\Error\Error;
use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\StringValueNode;

class DateTimeType extends ScalarType
{

    protected $format = 'Y-m-d H:i:s';

    protected $attributes = [
        'name' => 'DateTime',
        'description' => 'The `DateTime` scalar type represents a specific moment (date and time). It\'s JSON-value is a `string` formatted like "`yyyy-mm-dd hh:ii:ss`".'
    ];

    /** @inheritdoc */
    public function serialize($value)
    {
        return \Parse::date($value)->format($this->format);
    }

    /** @inheritdoc */
    public function parseValue($value)
    {
        return \Parse::date($value);
    }

    /** @inheritdoc */
    public function parseLiteral($valueNode)
    {
        try {
            if($valueNode instanceof StringValueNode) {
                return Carbon::parse($valueNode->value);
            } elseif($valueNode instanceof IntValueNode) {
                return Carbon::createFromTimestamp(intval($valueNode->value));
            } elseif($valueNode instanceof FloatValueNode) {
                $value = floatval($valueNode->value);
                return Carbon::createFromTimestampMs(round($value * 100));
            }
        } catch (\Exception $e) {
            throw new Error("Can't parse this value to a Carbon instance.", [$valueNode], null, null, $e);
        }

        throw new Error("Can't parse valueNode of type: {$valueNode->kind} to a Carbon instance.", [$valueNode]);
    }

}