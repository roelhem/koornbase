<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 18:22
 */

namespace Roelhem\RbacGraph\Contracts;


interface Edge
{

    /**
     * Returns the graph where this edge belongs to.
     *
     * @return Graph
     */
    public function getGraph();

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