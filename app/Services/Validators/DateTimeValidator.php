<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 10:29
 */

namespace App\Services\Validators;


use App\Services\Parsers\NotParsableException;
use Carbon\Carbon;
use Illuminate\Validation\Validator;

class DateTimeValidator
{

    /**
     * @param array $parameters
     * @param Validator $validator
     */
    protected function checkFieldParameters($parameters, $validator)
    {
        // Check if the parameters argument is an non-empty array
        if(!is_array($parameters) || count($parameters) === 0) {
            throw new \InvalidArgumentException("You need to specify one or more fieldnames to compare for the rule.");
        }

        // Get the data of the validator.
        $data = $validator->getRules();

        // loop trough each of the parameters
        foreach ($parameters as $parameter) {
            if(!array_has($data, $parameter)) {
                throw new \InvalidArgumentException("Can't find the field with name '$parameter'.");
            }
        }
    }

    /**
     * Compares the date value of the provided $value argument against all the values of the fields in the $parameters
     * argument using the provided $compareCallback argument.
     *
     * @param mixed $value
     * @param array $parameters
     * @param Validator $validator
     * @param callable $compareCallback
     * @param bool $allowUnparsable
     * @return bool
     */
    protected function compareDateAgainstFields($value, $parameters, $validator, $compareCallback,
                                                $allowUnparsable = true)
    {
        // Try to parse the value.
        try {
            $value = \Parse::date($value);

            // Check if the parameter fields are valid
            $this->checkFieldParameters($parameters, $validator);

            // Retrieve the validator data
            $data = $validator->getData();

            // Loop trough all the fields in the parameters
            foreach($parameters as $parameter) {
                $otherValue = array_get($data, $parameter);
                if(!$this->comareDateAgainstField(
                    $value,
                    $otherValue,
                    $validator,
                    $compareCallback,
                    $allowUnparsable
                )) {
                    return false;
                }
            }

            return true;

        } catch (NotParsableException $notParsableException) {
            return $allowUnparsable;
        }
    }

    /**
     * Compares the date value of the provided $value argument against another value.
     *
     * @param Carbon $value
     * @param mixed $otherValue
     * @param Validator $validator
     * @param callable $compareCallback
     * @param bool $allowUnparsable
     * @return bool
     */
    protected function comareDateAgainstField($value, $otherValue, $validator, $compareCallback,
                                              $allowUnparsable = true)
    {
        try {
            // Try to parse the value of the field parameters
            $otherValue = \Parse::date($otherValue);

            // compare the other value to the provided value with the given $compareCallback
            return $compareCallback($value, $otherValue);


        } catch (NotParsableException $otherNotParsableException) {
            return $allowUnparsable;
        }
    }

    /**
     * Checks if the provided date is before the date-values of the fields in the parameters.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validateBeforeFields($attribute, $value, $parameters, $validator)
    {
        return $this->compareDateAgainstFields($value, $parameters, $validator, function($value, $otherValue) {
            return $value < $otherValue;
        });
    }

    /**
     * Checks if the provided date is before or equals the date-values of the fields in the parameters.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validateBeforeOrEqualFields($attribute, $value, $parameters, $validator)
    {
        return $this->compareDateAgainstFields($value, $parameters, $validator, function($value, $otherValue) {
            return $value <= $otherValue;
        });
    }

    /**
     * Checks if the provided date is after the date-values of the fields in the parameters.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validateAfterFields($attribute, $value, $parameters, $validator)
    {
        return $this->compareDateAgainstFields($value, $parameters, $validator, function($value, $otherValue) {
            return $value > $otherValue;
        });
    }

    /**
     * Checks if the provided date is after or equals the date-values of the fields in the parameters.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validateAfterOrEqualFields($attribute, $value, $parameters, $validator)
    {
        return $this->compareDateAgainstFields($value, $parameters, $validator, function($value, $otherValue) {
            return $value >= $otherValue;
        });
    }

}