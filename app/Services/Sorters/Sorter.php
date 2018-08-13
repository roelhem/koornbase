<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 03:37
 */

namespace App\Services\Sorters;


use App\Enums\SortOrderDirection;
use Illuminate\Database\Eloquent\Builder;

class Sorter
{

    /**
     * Names of columns that can also be sorted by.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Returns an array with all the sortNames.
     *
     * @return array
     */
    public function list() {
        try {
            $class = new \ReflectionClass($this);

            $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

            $list = $this->columns;

            foreach ($methods as $method) {
                $shortName = $method->getShortName();
                if (str_is('sort*', $shortName)) {
                    $list[] = snake_case(str_after($shortName, 'sort'));
                }
            }
            return $list;

        } catch (\ReflectionException $exception) {
            return $this->columns;
        }
    }

    /**
     * Returns if the sort with the given name exists in this sort object.
     *
     * @param string $sortName
     * @return bool
     */
    public function has(string $sortName)
    {
        if(method_exists($this, $this->getMethodName($sortName))) {
            return true;
        } else if(in_array($sortName, $this->columns)) {
            return true;
        }
        return false;
    }

    /**
     * Adds an orderBy statement to an query.
     *
     * @param Builder $query
     * @param string $sortName
     * @param string|SortOrderDirection $direction
     * @return Builder
     */
    public function add($query, string $sortName, $direction = SortOrderDirection::ASC)
    {
        $callable = $this->getCallable($sortName);
        return $callable($query, SortOrderDirection::get($direction));
    }

    /**
     * @inheritdoc
     */
    public function addList($query, array $sortNameList)
    {
        foreach($sortNameList as $key => $value) {
            if(is_integer($key)) {
                if(str_is('*:asc', $value)) {
                    $query = $this->add($query, str_before($value, ':asc'), SortOrderDirection::ASC);
                } elseif(str_is('*:desc', $value)) {
                    $query = $this->add($query, str_before($value, ':desc'), SortOrderDirection::DESC);
                } else {
                    $query = $this->add($query, $value);
                }
            } else {
                if($value instanceof SortOrderDirection) {
                    $query = $this->add($query, $key, $value);
                } elseif(mb_strtolower($value) === 'desc') {
                    $query = $this->add($query, $key, SortOrderDirection::DESC);
                } else {
                    $query = $this->add($query, $key, SortOrderDirection::ASC);
                }
            }
        }

        return $query;
    }

    /**
     * Returns the callable that belongs to the given $sortName
     *
     * @param string $sortName
     * @return callable
     */
    protected function getCallable(string $sortName): callable
    {
        $methodName = $this->getMethodName($sortName);
        $result = [$this, $methodName];
        if(method_exists($this, $methodName) && is_callable($result)) {
            return $result;
        } else {
            if(in_array($sortName, $this->columns)) {
                /**
                 * @param Builder $query
                 * @param SortOrderDirection $direction
                 * @return Builder
                 */
                return function($query, $direction) use ($sortName) {
                    return $query->orderBy($sortName, $direction->getValue());
                };
            }
            throw new \OutOfBoundsException("Don't know how to order by the field '$sortName'.");
        }
    }

    /**
     * Returns the name of the method that should handle the sort with name $sortName if it exists.
     *
     * @param string $sortName
     * @return string
     */
    protected function getMethodName(string $sortName): string
    {
        $camelCase = camel_case($sortName);
        $methodName = 'sort'.ucfirst($camelCase);
        return $methodName;
    }

}