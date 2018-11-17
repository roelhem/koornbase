<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 22:12
 */

namespace App\Services\Validators;


use Illuminate\Validation\Validator;

class DatabaseValidator
{

    /**
     * Checks if the value of the attribute is unique for that column in the database, or has the same value as
     * the currently stored value in the target row.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validateUniqueOrSame($attribute, $value, $parameters, $validator) {
        $tableName = array_get($parameters, 0);
        $columnName = array_get($parameters, 1, $attribute);
        $keyAttribute = array_get($parameters, 2, 'id');
        $keyValue = array_get($validator->getData(), $keyAttribute);
        $primaryKey = array_get($parameters,3, 'id');

         return !\DB::table($tableName)
            ->where($columnName, '=',$value)
            ->where($primaryKey,'<>',$keyValue)
            ->exists();
    }
}