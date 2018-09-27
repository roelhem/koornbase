<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 18:22
 */

namespace Roelhem\GraphQL\Resolvers\Helpers;

class FieldNodeBFIterator extends FieldNodeIterator
{

    protected $queue = [];

    /** @inheritdoc */
    protected function add($element)
    {
        array_push($this->queue, $element);
    }

    /** @inheritdoc */
    protected function initState()
    {
        $this->queue = [];
    }

    /** @inheritdoc */
    protected function step()
    {
        return array_shift($this->queue);
    }

    /** @inheritdoc */
    protected function peek()
    {
        return array_get($this->queue, 0);
    }

}