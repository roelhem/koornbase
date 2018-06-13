<?php

namespace Roelhem\RbacGraph\Contracts;

use Roelhem\RbacGraph\Exceptions\EdgeNotAllowedException;

interface NodeBuilder extends Node
{

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
     * Assigns one or multiple roles to this node as children.
     *
     * @param array ...$children
     * @return $this
     * @throws EdgeNotAllowedException
     */
    public function assign( ...$children );

    /**
     * Assigns this node to one or multiple other objects.
     *
     * @param array ...$parents
     * @return $this
     * @throws EdgeNotAllowedException
     */
    public function assignTo( ...$parents );

    /**
     * @return Builder
     */
    public function getBuilder();

}