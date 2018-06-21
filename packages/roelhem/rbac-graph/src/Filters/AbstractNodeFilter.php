<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-06-18
 * Time: 22:23
 */

namespace Roelhem\RbacGraph\Filters;


use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\NodeFilter;

abstract class AbstractNodeFilter implements NodeFilter
{

    /**
     * @inheritdoc
     */
    public function __invoke($arg)
    {
        if ($arg instanceof Node) {
            return $this->includeNode($arg);
        }

        if(is_iterable($arg)) {
            return $this->filter($arg);
        }

        return $this->includeNode($arg);
    }

}