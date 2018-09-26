<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-09-18
 * Time: 21:47
 */

namespace Roelhem\GraphQL\Types;


use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\EnumValueDefinition;
use GraphQL\Type\Definition\Type;
use GraphQL\Utils\Utils;
use MabeEnum\Enum;
use MabeEnum\EnumMap;

class MabeEnumType extends EnumType
{
    const CLASS_CONFIG_NAME = 'class';

    public $class;

    protected $valueMap;

    public function __construct($config)
    {
        Utils::invariant(is_string($config[self::CLASS_CONFIG_NAME]), 'Must provide '.self::CLASS_CONFIG_NAME);

        if(!isset($config['name'])) {
            try {
                $reflection = new \ReflectionClass($config[self::CLASS_CONFIG_NAME]);
                $config['name'] = $reflection->getShortName();
            } catch (\ReflectionException $exception) {
                throw new InvariantViolation('The class has to be a valid className.', 0, $exception);
            }

        }


        parent::__construct($config);



        $this->class = $config[self::CLASS_CONFIG_NAME];
    }

    /**
     * @return Enum[]
     */
    protected function getEnumerators()
    {
        return call_user_func([$this->class, 'getEnumerators']);
    }

    /**
     * @param mixed $input
     * @return Enum
     */
    protected function getEnumerator($input)
    {
        return call_user_func([$this->class, 'getEnumerator']);
    }

    /**
     * @return EnumMap
     */
    public function getValueMap()
    {
        if($this->valueMap === null) {
            $this->valueMap = new EnumMap($this->class);

            foreach ($this->getEnumerators() as $enumerator) {
                $config = [
                    'name' => $enumerator->getName(),
                    'value' => $enumerator,
                ];

                if(isset($enumerator->description)) {
                    $config['description'] = $enumerator->description;
                }

                $this->valueMap->offsetSet($enumerator, new EnumValueDefinition($config));
            }
        }

        return $this->valueMap;
    }

    public function getValues()
    {
        $res = [];
        foreach ($this->getValueMap() as $key => $value) {
            $res[$key->getName()] = $value;
        }
        return $res;
    }


    public function assertValid()
    {
        Type::assertValid();

        Utils::invariant(
            isset($this->config[self::CLASS_CONFIG_NAME]),
            sprintf('%s has to have an class.', $this->name)
        );

        Utils::invariant(
            is_subclass_of($this->config[self::CLASS_CONFIG_NAME], Enum::class),
            sprintf('the referenced class of %s has to be an subclass of %s.', $this->name, Enum::class)
        );
    }


}