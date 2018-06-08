<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 18:57
 */

namespace App\Traits\Rbac;

/**
 * Trait HasFluentNameAndDescription
 *
 * Implements the shared, fluent setters and getters for the Rbac-interfaces
 *
 * @package App\Traits\Rbac
 */
trait ImplementRbacNode
{

    /**
     * Returns the ID of this node.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Allows to set the name in a fluent way and saves the model.
     *
     * @param string|null $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        $this->save();
        return $this;
    }

    /**
     * Returns the name of this node.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Allows to set the description in a fluent way and saves the model.
     *
     * @param string|null $description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;
        $this->save();
        return $this;
    }

    /**
     * Returns the description of this node.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

}