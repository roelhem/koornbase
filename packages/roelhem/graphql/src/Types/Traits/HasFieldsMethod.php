<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 06:38
 */

namespace Roelhem\GraphQL\Types\Traits;


use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Utils\Utils;
use Roelhem\GraphQL\Fields\Field;

/**
 * Trait HasFieldsCallback
 * @package Roelhem\GraphQL\Types\Traits
 * @mixin ObjectType
 */
trait HasFieldsMethod
{
    private $fields;

    /**
     * @return FieldDefinition[]
     * @throws InvariantViolation
     */
    public function getFields()
    {
        if($this->fields === null) {

            if(method_exists($this, 'getConnectionFields')) {
                $connectionFields = $this->getConnectionFields();
            } else {
                $connectionFields = [];
            }


            // Getting the field configurations
            $fields = array_merge(
                $this->getFieldSourcesFields(), // Fields from the fieldSources method,
                $connectionFields, // ConnectionFields
                $this->fields(), // Fields from the method
                array_get($this->config, 'fields', []) // Fields from the config
            );

            // Filling the fields array
            $this->fields = [];
            foreach ($fields as $key => $value) {
                $field = $this->getFieldDefinition($value, $key);
                $this->fields[$field->name] = $field;
            }

            // Also adding the interface fields, if not set yet.
            if(method_exists($this,'getInterfaces')) {
                $interfaces = $this->getInterfaces();
                foreach ($interfaces as $interface) {
                    foreach ($interface->getFields() as $field) {
                        if(!array_key_exists($field->name,$this->fields)) {
                            $this->fields[$field->name] = $field;
                        }
                    }
                }
            }
        }
        return $this->fields;
    }

    /**
     * Returns the FieldDefinition that belongs to the provided value
     *
     * @param string|FieldDefinition|Field|Type|array $value
     * @param string|mixed $key
     * @return FieldDefinition
     */
    protected function getFieldDefinition($value, $key = null) {
        // Resolve if a class-name was given.
        if (is_string($value)) {
            $value = app()->makeWith($value, ['config' => ['name' => $key]]);
        }

        // From instance
        if($value instanceof FieldDefinition) {
            return $value;
        }

        // From field configuration class
        if($value instanceof Field) {
            return $value->create();
        }

        // From Type instance
        if($value instanceof Type) {
            return FieldDefinition::create([
                'name' => is_string($key) ? $key : camel_case($value->name),
                'type' => $value
            ]);
        }

        // From config array
        if(is_array($value)) {
            // Create from array
            $value['name'] = array_get($value, 'name', $key);
            return FieldDefinition::create($value);
        }

        // No FieldDefinition found.
        throw new InvariantViolation("No field definition found for ".Utils::printSafe($value));
    }

    /**
     * @param string $name
     * @return FieldDefinition
     * @throws \Exception
     */
    public function getField($name)
    {
        if (null === $this->fields) {
            $this->getFields();
        }
        Utils::invariant(isset($this->fields[$name]), 'Field "%s" is not defined for type "%s"', $name, $this->name);
        return $this->fields[$name];
    }

    /**
     * Returns an array of other field-sources.
     *
     * @return array
     */
    protected function fieldSources()
    {
        return [];
    }

    /**
     * Returns an array that contains all the field configs from the field sources.
     *
     * @return array
     */
    protected function getFieldSourcesFields()
    {
        $res = [];
        foreach ($this->fieldSources() as $sourceKey => $sourceValue) {
            if(is_string($sourceKey)) {
                $res[$sourceKey] = $sourceValue;
            } elseif(is_array($sourceValue)) {
                foreach ($sourceValue as $key => $value) {
                    if(is_string($key)) {
                        $res[$key] = $value;
                    } else {
                        $res[] = $value;
                    }
                }
            } else {
                $res[] = $sourceValue;
            }
        }
        return $res;
    }


    /**
     * Returns the definitions of the fields of this Type.
     *
     * @return array
     */
    abstract protected function fields();

}