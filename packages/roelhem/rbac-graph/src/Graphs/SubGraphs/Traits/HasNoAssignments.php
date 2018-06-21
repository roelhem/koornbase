<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 00:09
 */

namespace Roelhem\RbacGraph\Graphs\SubGraphs\Traits;

use Roelhem\RbacGraph\Exceptions\AssignmentNotFoundException;

trait HasNoAssignments
{

    /**
     * @inheritdoc
     */
    public function getAssignments()
    {
        return collect([]);
    }

    /**
     * @inheritdoc
     */
    public function hasAssignment($assignable, $node)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getAssignment($assignable, $node)
    {
        throw new AssignmentNotFoundException;
    }

    /**
     * @inheritdoc
     */
    public function getNodeAssignments($node)
    {
        return collect([]);
    }

    /**
     * @inheritdoc
     */
    public function getAssignableAssignments($assignable)
    {
        return collect([]);
    }

    /**
     * @inheritdoc
     */
    public function getNodeAssignables($node)
    {
        return collect([]);
    }

    /**
     * @inheritdoc
     */
    public function getAssignedNodes($assignable)
    {
        return collect([]);
    }

}