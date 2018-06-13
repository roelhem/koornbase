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

}