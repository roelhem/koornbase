<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 23:03
 */

namespace Roelhem\RbacGraph\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Contract RbacDatabaseAssignable
 *
 * A contract for all the models that can have the nodes form the database implementation of the RbacGraph assigned
 * to it.
 *
 * @package Roelhem\RbacGraph\Contracts
 *
 * @property-read Collection|\Roelhem\RbacGraph\Database\Assignment[] $assignments
 * @property-read Collection|\Roelhem\RbacGraph\Database\Node[] $assignedNodes
 */
interface RbacDatabaseAssignable extends Assignable
{


    /**
     * The relation to all the assignments from the rbac-graph to this model.
     *
     * @return MorphMany
     */
    public function assignments();

    /**
     * Assigns the provided node to the assignable object.
     *
     * @param Node|string|integer $node
     */
    public function assignNode( $node );


    /**
     * The relation to all the nodes in the database graph that were assigned to this model.
     *
     * @return MorphToMany
     */
    public function assignedNodes();

}