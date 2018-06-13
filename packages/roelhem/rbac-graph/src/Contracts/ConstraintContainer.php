<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 12:09
 */

namespace Roelhem\RbacGraph\Contracts;


use Illuminate\Support\Collection;

interface ConstraintContainer
{

    /**
     * Returns all the constraints in this ConstraintContainer.
     *
     * @return Collection/Constraint[]
     */
    public function getConstraints();

    /**
     * Returns the constraint that belongs to the provided $constraint parameter.
     *
     * @param Constraint|string|integer $constraint  An instance, name or id of the searched $constraint
     * @return Constraint
     */
    public function getConstraint( $constraint );

    /**
     * Finds the constraint with the provided $id.
     *
     * @param integer $id
     * @return Constraint
     */
    public function getConstraintById( $id );

    /**
     * Finds the constraint with the provided $name.
     *
     * @param string $name
     * @return Constraint
     */
    public function getConstraintByName( $name );

    /**
     * Returns the id of the constraint that belongs to the provided $constraint parameter.
     *
     * @param Constraint|string|integer $constraint   An instance, name or id of the searched $constraint
     * @return integer
     */
    public function getConstraintId( $constraint );

    /**
     * Returns the name of the constraint that belongs to the provided $constraint parameter.
     *
     * @param Constraint|string|integer $constraint   An instance, name or id of the searched $constraint
     * @return string
     */
    public function getConstraintName( $constraint );

    /**
     * Returns if there exists a constraint in this container that can be found by the provided $constraint parameter.
     *
     * @param Constraint|string|integer $constraint   An instance, name or id of the searched $constraint
     * @return bool
     */
    public function hasConstraint( $constraint );

    /**
     * Returns if there exists a constraint in this container with the provided $id parameter.
     *
     * @param integer $id
     * @return bool
     */
    public function hasConstraintId( $id );

    /**
     * Returns if there exists a constraint in this container with the provided $name parameter.
     *
     * @param string $name
     * @return bool
     */
    public function hasConstraintName( $name );


}