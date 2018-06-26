<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 18:22
 */

namespace Roelhem\RbacGraph\Contracts\Edges;

use Roelhem\RbacGraph\Contracts\BelongsToGraph;
use Roelhem\RbacGraph\Contracts\Nodes\Node;

interface Edge extends BelongsToGraph
{

    /**
     * Returns the node on the 'parent' side of the edge.
     *
     * @return Node
     */
    public function getParent();

    /**
     * Returns the id of the node on the 'parent' side of the edge.
     *
     * @return integer
     */
    public function getParentId();

    /**
     * Returns the name of the node on the 'parent' side of the edge.
     *
     * @return string
     */
    public function getParentName();

    /**
     * Returns the the node on the 'child' side of the edge.
     *
     * @return Node
     */
    public function getChild();

    /**
     * Returns the id of the node on the 'child' side of the edge.
     *
     * @return integer
     */
    public function getChildId();

    /**
     * Returns the name of the node on the 'child' side of the edge.
     *
     * @return string
     */
    public function getChildName();

}