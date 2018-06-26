<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 22:30
 */

namespace App\Enums\Traits;

/**
 * Trait HasConfigFile
 *
 * Adds the values of a config-file to the Enum-elements
 *
 * @package App\Enums\Traits
 *
 * @property-read string  $name
 * @property-read string  $camel_name
 * @property-read mixed   $value
 * @property-read integer $ordinal
 */
trait HasConfigFile
{

    // ---------------------------------------------------------------------------------------------------------- //
    // -------- ABSTRACT METHODS -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the parsed content of the config file.
     *
     * @return array
     */
    abstract protected static function parseFile();

    /**
     * Returns the default config-file. This is used for the implementation of the magic methods.
     *
     * @return array
     */
    abstract protected function defaultConfig();

    // ---------------------------------------------------------------------------------------------------------- //
    // -------- LOAD THE CONFIG FILE ---------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The static parameter that keeps track of the parsed config-file.
     *
     * @var array|null
     */
    protected static $configArray = null;

    /**
     * A static function that initializes the config-array if it wasn't initialized yet. Otherwise, it returns
     * the stored value.
     *
     * @return array
     */
    protected static function getConfigArray() {
        if(static::$configArray === null) {
            static::$configArray = static::parseFile();
        }

        return static::$configArray;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // -------- RETRIEVE THE DATA ------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the key that is used in the config file for the values of the current element.
     *
     * @return string|integer
     */
    protected function getKey() {
        return $this->getName();
    }

    /**
     * Returns an array which is the part of the config file about the current element. Returns an empty
     * array if there was no config found for the current element.
     *
     * @return array
     */
    public function getConfig() {
        return array_get($this->getConfigArray(), $this->getKey(), []);
    }

    /**
     * Returns the value of the option with the provided $keyPath. If there was no value for that $keyPath in
     * the config-file, the value of $default will be returned.
     *
     * This function works in the same way as the `array_get()` helper from Laravel.
     *
     * @param string|integer|array $keyPath
     * @param mixed $default
     * @return mixed
     */
    public function conf($keyPath, $default = null) {
        return array_get($this->getConfig(), $keyPath, $default);
    }

    /**
     * Returns the value of the config with the provided $keyPath. If there was no value for that $keyPath in
     * the config-file, the value from the defaultConfig will be returned.
     *
     * @param string|integer|array $keyPath
     * @return mixed
     */
    protected function val($keyPath) {
        return $this->conf($keyPath, array_get($this->defaultConfig(), $keyPath));
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // -------- MAGIC PROPERTIES -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Returns the value of the config with the corresponding name.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if(!$this->__isset($name)) {
            trigger_error("The property $name is not found in the default config-file.");
        }

        switch ($name) {
            case 'name':       return $this->getName();
            case 'camel_name': return $this->getCamelCaseName();
            case 'value':      return $this->getValue();
            case 'ordinal':    return $this->getOrdinal();
            default:           return $this->val($name);
        }



    }

    /**
     * Returns if the config-entry with the given name exists.
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        $defaultKeys = [
            'name',
            'camel_name',
            'value',
            'ordinal'
        ];
        return in_array($name, $defaultKeys) || array_has($this->defaultConfig(), $name);
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // -------- FORMATTERS -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the name of this element in CamelCase with an leading capital letter.
     *
     * @return string
     */
    public function getCamelCaseName() {
        return mb_convert_case(camel_case($this->getName()), MB_CASE_TITLE);
    }

}