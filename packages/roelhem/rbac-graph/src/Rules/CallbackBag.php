<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 05:11
 */

namespace Roelhem\RbacGraph\Rules;

use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag as AttributeProviderContract;
use Roelhem\RbacGraph\Enums\RuleAttribute;
use Roelhem\RbacGraph\Exceptions\RuleAttributeEmptyException;

class CallbackBag implements AttributeProviderContract
{

    protected $definitions = [];

    protected $values = [];

    /**
     * CallbackBag constructor.
     * @param array $initValues
     */
    public function __construct($initValues = [])
    {
        foreach ($initValues as $key => $value) {
            $this->set($key, $value);
        }
    }

    /** @inheritdoc */
    public function get($attribute)
    {
        $name = RuleAttribute::get($attribute)->getValue();
        if(!array_key_exists($name, $this->values)) {
            $this->load($attribute);
        }
        return $this->values[$name];
    }

    /** @inheritdoc */
    public function getAll()
    {
        $res = [];
        foreach ($this->definitions as $key => $definition) {
            $res = $this->get($key);
        }
        return $res;
    }


    /**
     * Tries to load the value of the attribute.
     *
     * @param RuleAttribute|string $attribute
     * @throws RuleAttributeEmptyException
     */
    public function load($attribute)
    {
        $attribute = RuleAttribute::get($attribute);
        $name = $attribute->getValue();

        if(array_key_exists($name, $this->definitions)) {
            $definition = $this->definitions[$name];
            if(is_callable($definition)) {
                $value = $definition();
            } else {
                $value = $definition;
            }
            $this->values[$name] = $value;
        } else {
            try {
                $this->values[$name] = $attribute->getDefault($this);
            } catch (RuleAttributeEmptyException $exception) {
                throw new RuleAttributeEmptyException("Can't load the attribute value of the attribute $attribute.", 0, $exception);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function hasExplicit($attribute)
    {
        if(!($attribute instanceof RuleAttribute) && !RuleAttribute::has($attribute)) {
            return false;
        }

        $name = RuleAttribute::get($attribute)->getValue();
        if(array_key_exists($name, $this->definitions)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function has($attribute)
    {
        if(!($attribute instanceof RuleAttribute) && !RuleAttribute::has($attribute)) {
            return false;
        }

        if($this->hasExplicit($attribute)) {
            return true;
        }

        return RuleAttribute::get($attribute)->hasDefault($this);
    }

    /**
     * @inheritdoc
     */
    public function set($attribute, $value) {
        $name = RuleAttribute::get($attribute)->getValue();
        $this->definitions[$name] = $value;
    }

    /**
     * @inheritdoc
     */
    public function unset($attribute) {
        $name = RuleAttribute::get($attribute)->getValue();
        unset($this->definitions[$name]);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTATION: magic properties ------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @inheritdoc
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function __unset($name)
    {
        $this->unset($name);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTATION: ArrayAccess ------------------------------------------------------------------------ //
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
        $this->unset($offset);
    }
}