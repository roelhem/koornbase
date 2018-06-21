<?php

namespace Roelhem\RbacGraph\Contracts;

interface NodeBuilder extends BelongsToGraph
{

    /**
     * Returns the node that this NodeBuilder manages.
     *
     * @return Node
     */
    public function getNode();

    /**
     * Returns the builder instance where this NodeBuilder belongs to.
     *
     * @return Builder
     */
    public function getBuilder();

    /**
     * Sets the title of this node in a fluent way.
     *
     * @param string|null $title
     * @return $this
     */
    public function title( $title );

    /**
     * Sets the description of this node in a fluent way.
     *
     * @param null|string $description
     * @return $this
     */
    public function description( $description );

    /**
     * Sets the options in a fluent way. The keys of the array refer to the option keys you want to set and the values
     * the value you want to update.
     *
     * @param array $options
     * @return $this
     */
    public function options( $options );

    /**
     * Assigns one or multiple roles to this node as children.
     *
     * @param array ...$children
     * @return $this
     */
    public function assign( ...$children );

    /**
     * Assigns this node to one or multiple other objects.
     *
     * @param array ...$parents
     * @return $this
     */
    public function assignTo( ...$parents );

}