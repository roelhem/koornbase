<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 10:59
 */

namespace App\Http\GraphQL\Queries\Traits;


use Illuminate\Database\Eloquent\Builder;

trait HasModelClassName
{

    /**
     * The class name of the model.
     *
     * @var string $modelClass
     */
    protected $modelClass;

    /**
     * The cashed reflection class.
     *
     * @var \ReflectionClass|null $reflection
     */
    protected $reflection;

    /**
     * Gives the reflection class for the model class name.
     *
     * @return \ReflectionClass
     */
    protected function getReflectionClass()
    {
        try {
            if ($this->reflection === null) {
                $this->reflection = new \ReflectionClass($this->modelClass);
            }
        } catch (\ReflectionException $exception) {
            throw new \LogicException("Couldn't make a refection class from $this->modelClass in ".get_class($this), 0, $exception);
        }

        return $this->reflection;
    }


    /**
     * Gives the typeName from the model class name.
     *
     * @return string
     */
    protected function getTypeName()
    {
        return $this->getReflectionClass()->getShortName();
    }


    /**
     * @return Builder
     */
    protected function getQuery()
    {
        $modelClass = $this->modelClass;
        return $modelClass::query();
    }


}