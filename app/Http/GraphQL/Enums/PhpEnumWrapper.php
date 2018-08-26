<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 22:33
 */

namespace App\Http\GraphQL\Enums;


use MabeEnum\Enum;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PhpEnumWrapper extends GraphQLType
{

    protected $enumObject = true;

    protected $enumClass;

    /**
     * PhpEnumWrapper constructor.
     * @param $enumClass
     * @param array $attributes
     */
    public function __construct($enumClass, $attributes = [])
    {
        if(!is_string($enumClass) || !is_subclass_of($enumClass,Enum::class)) {
            throw new \InvalidArgumentException("The argument 'enumClass' has to be a className of a class that is a subclass of ". Enum::class.".");
        }
        $this->enumClass = $enumClass;
        parent::__construct($attributes);
    }

    public function attributes()
    {
        try {
            $reflection = new \ReflectionClass($this->enumClass);
            $name = $reflection->getShortName();
        } catch (\ReflectionException $exception) {
            $name = $this->enumClass;
        }

        /** @var Enum[] $enumerators */
        $enumerators = call_user_func([$this->enumClass, 'getEnumerators']);

        $values = [];
        foreach ($enumerators as $enumerator) {
            if(isset($enumerator->description)) {
                $values[$enumerator->getName()] = [
                    'value' => $enumerator,
                    'description' => $enumerator->description
                ];
            } else {
                $values[$enumerator->getName()] = $enumerator;
            }
        }

        return [
            'name' => $name,
            'values' => $values,
        ];
    }

}