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
use GraphQL\Utils\Utils;

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
            $configFields = parent::getFields();

            $methodFields = FieldDefinition::defineFieldMap($this, $this->fields());

            $this->fields = array_merge($configFields, $methodFields);

            foreach ($this->fieldSources() as $fieldSource) {
                $this->fields = array_merge($this->fields, FieldDefinition::defineFieldMap($this, $fieldSource));
            };

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
     * Returns the definitions of the fields of this Type.
     *
     * @return array
     */
    abstract protected function fields();

}