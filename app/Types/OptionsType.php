<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-05-18
 * Time: 23:55
 */

namespace App\Types;

use App\Exceptions\OptionNotFoundException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class OptionsType
 *
 * This class models the information that can be stored into an `options` column.
 *
 * @package App\Types
 */
class OptionsType implements \ArrayAccess, Arrayable, Jsonable
{

    /**
     * @var array|null Contains all the explicit values of this OptionsType.
     */
    protected $values = [];

    /**
     * @var array Contains all the default values of this OptionsType.
     */
    protected $defaultValues = [];

    /**
     * @var callable|null A callable that should be called every time this OptionsType is changed.
     */
    protected $onChange = null;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INITIALISATION ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * OptionsType constructor.
     *
     * @param array $defaultValues
     * @param string|array|null $values
     * @param callable|null $onChange
     * @throws OptionNotFoundException
     */
    public function __construct($defaultValues, $values = null, $onChange = null)
    {
        $this->defaultValues = $defaultValues;
        $this->onChange = $onChange;

        if($values !== null) {

            if(is_string($values)) {
                $values = json_decode($values, true);
            }

            foreach ($values as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MAIN METHODS --------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Triggers the onChange function of this OptionType.
     */
    protected function changed() {

        $onChange = $this->onChange;

        if(is_callable($onChange)) {
            $onChange($this);
        }
    }

    /**
     * Returns if there is an option with the given $key.
     *
     * @param string $key
     * @return bool
     */
    public function has($key) {
        if(!is_string($key)) {
            return false;
        }
        return array_key_exists($key, $this->defaultValues);
    }

    /**
     * Checks if there exists an option with the given $key. Throws an OptionNotFoundException otherwise.
     *
     * Throws an error if it can't find the option in the Default Values array of this OptionsType.
     *
     * @param string $key
     * @throws OptionNotFoundException
     */
    protected function check($key) {
        if(!$this->has($key)) {
            throw new OptionNotFoundException($this, $key);
        }
    }

    /**
     * Sets the value of the option with the specified $key to the given $value.
     *
     * This function changes the value of OptionsType an thus triggers the $onChange callable if provided by
     * the initialization.
     *
     * @param string $key
     * @param mixed $value
     * @throws OptionNotFoundException
     */
    public function set($key, $value) {
        $this->check($key);

        $this->values[$key] = $value;

        $this->changed();
    }

    /**
     * Resets the value of the option with the specified $key to the default value.
     *
     * This function changes the value of OptionsType an thus triggers the $onChange callable if provided by
     * the initialization.
     *
     * @param string $key
     * @throws OptionNotFoundException
     */
    public function reset($key) {
        $this->check($key);

        if($this->values !== null) {
            unset($this->values[$key]);
        }

        $this->changed();
    }

    /**
     * Gets the value of the option with the specified $key.
     *
     * @param string $key
     * @return mixed
     * @throws OptionNotFoundException
     */
    public function get($key) {
        $this->check($key);
        return array_get($this->values, $key, $this->defaultValues[$key]);
    }

    /**
     * Gets the default value for the given option.
     *
     * @param string $key
     * @return mixed
     * @throws OptionNotFoundException
     */
    public function getDefault($key) {
        $this->check($key);
        return $this->defaultValues[$key];
    }

    /**
     * Returns whether or not the option with the given $key is explicitly set.
     *
     * @param string $key
     * @return bool
     * @throws OptionNotFoundException
     */
    public function isExplicit($key) {
        $this->check($key);
        return array_key_exists($key, $this->values);
    }

    /**
     * Returns an array of all the option values of this OptionsType.
     *
     * This includes the default values.
     *
     * @return array
     */
    public function getAll() {
        $result = [];
        foreach ($this->defaultValues as $key => $defaultValue) {
            $result[$key] = array_get($this->values, $key, $defaultValue);
        }
        return $result;
    }

    /**
     * Returns an array with all the values of this OptionsType that are explicitly set.
     *
     * This will not return the default values where just the default values should be used as the value of
     * an option. This array contains enough information to reconstruct this OptionsType (in combination with
     * the default values array.)
     *
     * @return array
     */
    public function getExplicit() {
        return $this->values;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- PROPERTY OVERLOADING ------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @param string $name
     * @param mixed $value
     * @throws OptionNotFoundException
     */
    public function __set(string $name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws OptionNotFoundException
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name)
    {
        return $this->has($name);
    }

    /**
     * @param string $name
     * @throws OptionNotFoundException
     */
    public function __unset(string $name)
    {
        $this->reset($name);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INTERFACE IMPLEMENTATION: ArrayAccess -------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        $this->reset($offset);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INTERFACE IMPLEMENTATION: Arrayable ---------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return $this->getAll();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INTERFACE IMPLEMENTATION: Jsonable ----------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function toJson($options = JSON_FORCE_OBJECT)
    {
        return json_encode( $this->getExplicit(), $options);
    }

}