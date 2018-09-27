<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 19:25
 */

namespace Roelhem\GraphQL\Resolvers\Helpers;


use Roelhem\GraphQL\Resolvers\ResolveStore;

abstract class FieldNodeIterator implements \Iterator
{

    protected $store;

    protected $counter = 0;

    protected $maxDepth;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INITIALIZE ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * QueryIterator constructor.
     * @param ResolveStore $store
     * @param null|integer $maxDepth
     */
    public function __construct(ResolveStore $store, $maxDepth = null)
    {
        $this->store = $store;
        $this->maxDepth = $maxDepth;
        $this->rewind();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ABSTRACT METHODS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Initializes the variables that store the state of the iterator.
     *
     * @return void
     */
    abstract protected function initState();

    /**
     * Sets the iterator one step forward and returns the last value.
     *
     * @return FieldNodeHelper
     */
    abstract protected function step();

    /**
     * Returns the current value of the iterator.
     *
     * @return FieldNodeHelper|null
     */
    abstract protected function peek();

    /**
     * Adds an new element to the iterator when a new element is discovered.
     *
     * @param FieldNodeHelper $element
     */
    abstract protected function add($element);


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- TYPE HANDLERS -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $invertLists = false;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTS: \Iterator ------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the current value of the iterator.
     *
     * @return FieldNodeHelper
     */
    public function current()
    {
        return $this->peek();
    }

    /**
     * Returns if the current state is valid.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->peek() !== null;
    }

    /**
     * Sets the iterator to the next state.
     *
     * @return void
     */
    public function next()
    {
        $fieldHelper = $this->step();
        if($this->maxDepth === null || $this->maxDepth > $fieldHelper->localDepth) {
            foreach ($fieldHelper->getChildren() as $child) {
                $this->add($child);
            }
        }
        $this->counter++;
    }

    /**
     * Returns the key for the current item.
     *
     * @return int|string|null
     */
    public function key()
    {
        return $this->counter;
    }


    /**
     * Rewinds the state of the iterator
     * @return void
     */
    public function rewind()
    {
        $this->initState();
        $this->counter = 0;
        foreach ($this->store->fieldNodes as $fieldNode) {
            $this->add(new FieldNodeHelper($this->store, $fieldNode, $this->store->path, 0));
        }
    }

}