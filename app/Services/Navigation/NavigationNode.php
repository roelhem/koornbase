<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 01-05-18
 * Time: 23:12
 */

namespace App\Services\Navigation;

use ArrayIterator;
use IteratorAggregate;
use ArrayAccess;
use Countable;
use App\Exceptions\NavigationItemNotFoundException;


/**
 * Class NavigationNode
 * @package App\Services\Navigation
 */
class NavigationNode implements ArrayAccess, IteratorAggregate, Countable
{

    /**
     * @var NavigationItemRepository
     */
    protected $repository;

    /**
     * @var NavigationItem
     */
    protected $item;

    /**
     * @var NavigationNode[]
     */
    public $children = [];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INITIALIZATION ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * NavigationNode constructor.
     *
     * @param NavigationItem|string $item
     * @param array $children
     * @param NavigationItemRepository|null $repository
     * @throws NavigationItemNotFoundException
     */
    public function __construct($item, $children = [], NavigationItemRepository $repository = null) {
        if ($repository === null) {
            $this->repository = app(NavigationItemRepository::class);
        } else {
            $this->repository = $repository;
        }

        $this->item = $this->repository->require($item);

        foreach ($children as $key => $value) {
            if (is_string($key)) {
                $child = $this->parse($key, $value);
            } else {
                $child = $this->parse($value);
            }

            if($child !== null) {
                $this->children[] = $child;
            }
        }
    }

    /**
     * Tries to create a node from the given $item and $children input
     *
     * @param mixed $item
     * @param mixed $children
     * @return NavigationNode|null
     * @throws NavigationItemNotFoundException
     */
    protected function parse($item, $children = null) {
        if ($item instanceof NavigationNode) {
            return $item;
        }

        if($children === null) {
            $children = [];
        } elseif(!is_array($children)) {
            $children = [$children];
        }

        if(is_string($item) || ($item instanceof NavigationItem)) {
            return new NavigationNode($item, $children, $this->repository);
        }

        return null;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- TREE FUNCTIONS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the shortest path from the item in this node to the item that matches the request.
     *
     * @param callable $callable
     * @return NavigationItem[]
     */
    public function path($callable) {
        //TODO: Implement a breath-fist search algorithm instead of a dept-first algorithm.

        // check itself:
        if($callable($this->item)) {
            return [$this->item];
        }

        // check children:
        foreach ($this->children as $child) {
            $path = $child->path($callable);

            if(count($path) > 0) {
                array_unshift($path, $this->item);
                return $path;
            }
        }

        return [];
    }


    public function pathTo($id) {
        return $this->path(function(NavigationItem $item) use ($id) {
            return $item->id == $id;
        });
    }

    public function pathToCurrent() {
        return $this->path(function(NavigationItem $item) {
            return $item->matchesRequest();
        });
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ATTRIBUTES ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        return $this->item->__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        $this->item->__set($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->item->__isset($name);
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        $this->item->__unset($name);
    }

    /**
     * @inheritdoc
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->item, $name], $arguments);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->item->offsetExists($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->item->offsetGet($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        return $this->item->offsetSet($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        $this->item->offsetUnset($offset);
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->children);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->children);
    }

}