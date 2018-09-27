<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 19:23
 */

namespace Roelhem\GraphQL\Resolvers\Helpers;


use GraphQL\Language\AST\FieldNode;

class FieldNodeDFIterator extends FieldNodeIterator
{
    /** @var FieldNode[] $stack */
    public $stack = [];

    protected $invertLists = true;

    /** @inheritdoc */
    protected function add($element)
    {
        array_push($this->stack, $element);
    }

    /** @inheritdoc */
    protected function initState()
    {
        $this->stack = [];
    }

    /** @inheritdoc */
    protected function peek()
    {
        return array_get($this->stack, count($this->stack)-1);
    }

    /** @inheritdoc */
    protected function step()
    {
        return array_pop($this->stack);
    }
}