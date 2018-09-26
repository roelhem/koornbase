<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 01:07
 */

namespace Roelhem\GraphQL\Repositories;


use GraphQL\Type\Definition\Type;
use Roelhem\GraphQL\Contracts\TypeRepositoryContract;
use Roelhem\GraphQL\Exceptions\TypeNotFoundException;
use Roelhem\GraphQL\Repositories\Traits\GetAllFromGetNames;
use Roelhem\GraphQL\Types\MabeEnumType;

class EnumTypeRepository implements TypeRepositoryContract
{
    use GetAllFromGetNames;

    protected $types;

    public function __construct($enumTypes = [])
    {
        $this->types = [];
        foreach ($enumTypes as $key => $value) {
            if(is_int($key)) {
                if(is_string($value)) {
                    $name = $this->typeNameFromClassName($value);
                    $this->types[$name] = [
                        'name' => $name,
                        MabeEnumType::CLASS_CONFIG_NAME => $value,
                    ];
                } elseif(is_array($value)) {
                    if(isset($value['name'])) {
                        $name = $value['name'];
                    } elseif(isset($value[MabeEnumType::CLASS_CONFIG_NAME])) {
                        $name = $this->typeNameFromClassName($value[MabeEnumType::CLASS_CONFIG_NAME]);
                        $value['name'] = $name;
                    } else {
                        throw new \UnexpectedValueException("Can't find a name for a Enum-type.");
                    }
                    $this->types[$name] = $value;
                } elseif($value instanceof Type) {
                    $this->types[$value->name] = $value;
                } else {
                    throw new \UnexpectedValueException("Can't create a type from the provided config-value.");
                }
            } elseif(is_string($key)) {
                if(is_string($value)) {
                    $this->types[$key] = [
                        'name' => $key,
                        MabeEnumType::CLASS_CONFIG_NAME => $value,
                    ];
                } elseif(is_array($value)) {
                    $name = array_get($value, 'name', $key);
                    $value['name'] = $name;
                    $this->types[$name] = $value;
                } elseif($value instanceof Type) {
                    $name = $value->name;
                    $this->types[$name] = $value;
                } else {
                    throw new \UnexpectedValueException("Can't create a type from the provided config-value.");
                }
            }
        }
    }

    protected function typeNameFromClassName($className) {
        try {
            $reflection = new \ReflectionClass($className);
            return $reflection->getShortName();
        } catch (\ReflectionException $reflectionException) {
            throw new \UnexpectedValueException("Didn't recognize string '$className'' as classname.", 0, $reflectionException);
        }
    }


    /** @inheritdoc */
    public function get($typeName)
    {
        if(!array_key_exists($typeName, $this->types)) {
            throw new TypeNotFoundException($typeName, $this);
        }

        $type = $this->types[$typeName];

        if(is_array($type)) {
            $type = new MabeEnumType($type);
            $this->types[$typeName] = $type;
        }

        if($type instanceof Type) {
            return $type;
        }

        throw new TypeNotFoundException($typeName, $this);
    }

    /** @inheritdoc */
    public function has($typeName)
    {
        return array_key_exists($typeName, $this->types);
    }

    /**
     * @return string[]
     */
    public function getNames()
    {
        return array_keys($this->types);
    }

}