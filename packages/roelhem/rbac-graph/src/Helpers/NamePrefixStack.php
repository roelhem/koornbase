<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 07:44
 */

namespace Roelhem\RbacGraph\Helpers;


use Roelhem\RbacGraph\Contracts\BelongsToGraph;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Traits\HasGraphProperty;

class NamePrefixStack implements BelongsToGraph, \Countable
{

    use HasGraphProperty;

    /**
     * The stack with prefixes.
     *
     * @var string[]
     */
    protected $stack = [];

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  INITIALISATION  -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * NamePrefixHelper constructor.
     * @param Graph $graph
     */
    public function __construct(Graph $graph)
    {
        $this->initGraph($graph);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  STACK MANIPULATION  ---------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Pushes a new prefix on the stack
     *
     * @param string $prefix
     */
    public function push(string $prefix)
    {
        array_push($stack, $prefix);
    }

    /**
     * Pops the last prefix from the stack.
     *
     * @return string
     */
    public function pop()
    {
        return array_pop($stack);
    }

    /**
     * Returns the number of prefix parts stored in this namePrefixStack.
     *
     * @return int
     */
    public function count()
    {
        return count($this->stack);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  STRING MANIPULATION  --------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns all the prefix parts upto a provided $depth. If $depth is `null`, all the prefix parts will be
     * returned.
     *
     * @param int|null $depth
     * @return string[]
     */
    public function prefixParts(?int $depth = null)
    {
        if($depth === null) {
            return $this->stack;
        } else {
            return array_slice($this->stack, 0, $depth);
        }
    }

    /**
     * Prefixes the provided value with the prefix. Leave $value empty to only return the prefix string.
     *
     * The $depth parameter determines how many prefixes should be added to the $value. If $depth is equal to
     * null, all the prefixes will be added.
     *
     * @param null|string $value
     * @param null|integer $depth
     * @return string
     */
    public function prefix(?string $value = null, ?int $depth = null)
    {
        $parts = $this->prefixParts($depth);
        $prefix = implode('', $parts);
        if($value === null) {
            return $prefix;
        } else {
            return $value;
        }
    }

    /**
     * Returns the full name of the abbriviated name based on the current prefix parts.
     *
     * @param null|string $abbr
     * @return string
     */
    public function name(?string $abbr = null)
    {
        for ($i = 0; $i <= $this->count(); $i++) {
            $name = $this->prefix($abbr, $i);
            if($this->getGraph()->hasNodeName($name)) {
                return $name;
            }
        }
        return $this->prefix($abbr);
    }

    /**
     * Function that prefixes the input argument only if it is a string. All other values will be passed
     * without any changes.
     *
     * @param mixed|Node|integer|string $input
     * @return mixed|Node|integer|string
     */
    public function node($input) {
        if(is_string($input)) {
            return $this->name($input);
        }
        return $input;
    }

}