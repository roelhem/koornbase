<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 22:40
 */

namespace App\Http\Resources\Api\Support;


trait hasGetterMethods
{
    /**
     * @throws \ReflectionException
     */
    public function getGetterMethods() {
        // Get the methods
        $reflectionClass = new \ReflectionClass($this->resource);
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);

        // Prepare the results
        $result = [];

        foreach ($methods as $method) {
            $shortName = $method->getShortName();
            if(starts_with($shortName, 'get')) {
                $keyName = lcfirst(str_after($shortName, 'get'));
                $result[$keyName] = $method->invoke($this->resource);
            }
        }

        return $result;
    }
}