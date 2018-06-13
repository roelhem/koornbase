<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 17:09
 */

namespace Roelhem\RbacGraph\Contracts;


interface Constraint
{

    /**
     * Returns the id of this constraint.
     *
     * @return integer
     */
    public function getId();


    /**
     * Returns the name of this constraint.
     *
     * @return string
     */
    public function getName();


    /**
     * Return the title of this constraint.
     *
     * @return string
     */
    public function getTitle();


    /**
     * Returns the description of this constraint.
     *
     * @return string
     */
    public function getDescription();


}