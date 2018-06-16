<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 02:53
 */

namespace Roelhem\RbacGraph\Contracts;


interface MutableNode extends Node
{

    /**
     * Sets the title of this node to the provided $title.
     *
     * @param string|null $title
     * @return void
     */
    public function setTitle( $title );

    /**
     * Sets the description of this node to the provided $description.
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription( $description );

    /**
     * Sets the option with the provided key for this node.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setOption($key, $value);

}