<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 17:33
 */

namespace App\Services\Validators;
use App\Services\Parsers\NotParsableException;
use Illuminate\Validation\Validator;


/**
 * Class AfterValidation
 *
 * Some extra (complicated) validation steps that can be run after the normal validation had succeeded.
 *
 * @package App\Services\Validators
 */
class AfterValidation
{

    /**
     * A reference to the validator that was completed.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * @var array
     */
    protected $defaults = [];

    /**
     * AfterValidation constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $defaults
     */
    public function setDefaults($defaults) {
        $this->defaults = $defaults;
    }

    /**
     * Returns the value of a validated field in the validator.
     *
     * @param string|array $key
     * @param mixed $default
     * @return mixed
     */
    public function getValue($key, $default = null)
    {
        return array_get($this->validator->getData(), $key, array_get($this->defaults, $key, $default));
    }


    /**
     * Ensures that the fields are in the order of the provided array. The array should contain the names of date or
     * datetime fields.
     *
     * @param $fields
     * @param boolean $alertEarliest
     */
    public function ensureChronology($fields, $alertEarliest = false)
    {

        // Get the values and throw away all the invalid date values.
        $entries = [];
        foreach ($fields as $field) {
            try {
                $entries[] = [
                    'field' => $field,
                    'value' => \Parse::date($this->getValue($field)),
                ];
            } catch (NotParsableException $e) {}
        }

        // Loop trough every field entry and check if it is smaller then the next one.
        $entryCount = count($entries);
        foreach ($entries as $index => $entry) {
            for ($i=$index;$i < $entryCount; $i++) {
                if($entry['value'] > $entries[$i]['value']) {
                    $this->validator->errors()->add(
                        $alertEarliest ? $entry['field'] : $entries[$i]['field'],
                        $entry['field'].' moet vóór '.$entries[$i]['field'].' zijn.'
                    );
                }
            }
        }
    }

}