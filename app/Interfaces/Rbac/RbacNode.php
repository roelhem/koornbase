<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 19:06
 */

namespace App\Interfaces\Rbac;

/**
 * Interface RbacNode
 *
 * An interface that is implemented by both roles and permissions
 *
 * @package App\Interfaces\Rbac
 */
interface RbacNode
{

    /**
     * Returns the id of this node.
     *
     * @return string
     */
    public function getId();

    /**
     * Sets the name of this node in a fluent way.
     *
     * @param $name
     * @return $this
     */
    public function name($name);

    /**
     * Returns the name of this node.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the description of this node in a fluent way.
     *
     * @param $description
     * @return $this
     */
    public function description($description);

    /**
     * Returns the description of this node.
     *
     * @return string
     */
    public function getDescription();

}