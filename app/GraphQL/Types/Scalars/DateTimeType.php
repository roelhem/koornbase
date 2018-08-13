<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 18:04
 */

namespace App\GraphQL\Types\Scalars;


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

        if(is_string($value)) {
            $value = Carbon::parse($value);
        } elseif (is_integer($value)) {
            $value = Carbon::createFromTimestamp($value);
        } elseif(is_float($value)) {
            $value = Carbon::createFromTimestampMs(round($value * 100));
        }

        if($value instanceof \DateTimeInterface) {
            return $value->format($this->format);
        } else {
            return null;
        }
    }

    /** @inheritdoc */
    public function parseValue($value)
    {
        if(is_string($value)) {
            return Carbon::parse($value);
        } elseif (is_integer($value)) {
            return Carbon::createFromTimestamp($value);
        } else {
            return null;
        }
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