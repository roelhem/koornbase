<?php

namespace Roelhem\RbacGraph\Contracts;

use Roelhem\RbacGraph\Enums\NodeType;

/**
 * Interface NodeContract
 *
 * An interface for a node in a Rbac-graph.
 */
interface Node extends BelongsToGraph
{

    /**
     * Returns the unique identifier integer for this node in the graph.
     *
     * @return integer
     */
    public function getId();

    /**
     * Returns the string that uniquely identifies this node in the graph.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the type of this NodeContract. The value is a value in the NodeTypes enum.
     *
     * @return NodeType
     */
    public function getType();

    /**
     * Returns the title of this node.
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Returns the description of this node.
     * 
     * @return string|null
     */
    public function getDescription();

    /**
     * Returns the option values of this node.
     *
     * @return array
     */
    public function getOptions();

    /**
     * Returns the option with the specified key. If the key doesn't exist, the default value is returned.
     *
     * @param string|array|integer $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption($key, $default = null);

}