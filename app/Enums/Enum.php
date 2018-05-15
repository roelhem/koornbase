<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-05-18
 * Time: 01:31
 */

namespace App\Enums;
use BenSampo\Enum\Enum as OldEnum;

class Enum extends OldEnum
{

    /**
     * @param string|null $value
     * @return array
     * @throws \ReflectionException
     */
    public static function asArray($value = null): array
    {
        if($value === null) {

            $results = [];
            foreach (static::getValues() as $value) {
                $results[] = static::asArray($value);
            }
            return $results;

        } elseif(in_array($value, static::getValues())) {

            $result = [
                'value' => $value,
            ];

            $reflectionClass = new \ReflectionClass(static::class);

            $methods = $reflectionClass->getMethods();

            foreach ($methods as $method) {
                // A FUNCTION STARTING WITH GET WILL BE ADDED AS PARAMETER
                if (starts_with($method->name, 'get') && $method->isPublic()
                        && $method->isStatic() && !in_array($method->name, [
                            'getKey','getValue','getKeys','getValues','getDescription','getRandomKey','getRandomValue'
                    ])) {

                    // Add the get value as a param to the result.
                    $paramName = str_after($method->name, 'get');
                    $paramValue = call_user_func([static::class, $method->name], $value);

                    // Add the param to the result.
                    $result[camel_case($paramName)] = $paramValue;
                }
            }

            return $result;
        } else {
            return null;
        }
    }

    /**
     * @param array|string $ordening
     * @return array
     * @throws \ReflectionException
     */
    public static function asOrderedArray($ordening): array
    {
        if(is_string($ordening)) {
            $ordeningMethodName = 'ordening'.ucfirst($ordening);
            $ordening = call_user_func([static::class, $ordeningMethodName]);
        }

        if(!is_array($ordening)) {
            return [];
        }

        $results = [];
        foreach ($ordening as $value) {
            $results[] = static::asArray($value);
        }
        return $results;
    }

}