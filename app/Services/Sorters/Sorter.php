<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 03:37
 */

namespace App\Services\Sorters;


use App\Exceptions\SortNameNotFoundException;

class Sorter
{

    /**
     * @inheritdoc
     */
    public function list():array {
        try {
            $class = new \ReflectionClass($this);

            $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

            $list = [];

            foreach ($methods as $method) {
                $shortName = $method->getShortName();
                if (str_is('sort*', $shortName)) {
                    $list[] = kebab_case(str_after($shortName, 'sort'));
                }
            }
            return $list;

        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    /**
     * @inheritdoc
     */
    public function has(string $sortName): bool
    {
        return method_exists($this, $this->getMethodName($sortName));
    }

    /**
     * @inheritdoc
     */
    public function add($query, string $sortName, bool $desc = false)
    {
        $callable = $this->getCallable($sortName);
        return $callable($query, $desc);
    }

    /**
     * @inheritdoc
     */
    public function addList($query, array $sortNameList)
    {
        foreach($sortNameList as $key => $value) {
            if(is_integer($key)) {
                if(str_is('*:asc', $value)) {
                    $query = $this->add($query, str_before($value, ':asc'), false);
                } elseif(str_is('*:desc', $value)) {
                    $query = $this->add($query, str_before($value, ':desc'), true);
                } else {
                    $query = $this->add($query, $value);
                }
            } else {
                if(mb_strtolower($value) === 'desc') {
                    $query = $this->add($query, $key, true);
                } else {
                    $query = $this->add($query, $key);
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
     * @throws SortNameNotFoundException
     */
    protected function getCallable(string $sortName): callable
    {
        $methodName = $this->getMethodName($sortName);
        $result = [$this, $methodName];
        if(method_exists($this, $methodName) && is_callable($result)) {
            return $result;
        } else {
            throw new SortNameNotFoundException("The sort with name '$sortName' was not found in ".self::class.". (Failed to create the callable).");
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