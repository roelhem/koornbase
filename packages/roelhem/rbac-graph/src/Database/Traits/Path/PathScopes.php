<?php

namespace Roelhem\RbacGraph\Database\Traits\Path;

use Illuminate\Database\Eloquent\Builder;
use Roelhem\RbacGraph\Contracts\Node as NodeContract;
use Roelhem\RbacGraph\Contracts\Edge as EdgeContract;
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;


/**
 * Trait PathScopes
 *
 *
 * @package Roelhem\RbacGraph\Database\Traits\Path
 *
 *
 * @method static Builder startsAt(NodeContract|string|integer $node)
 * @method static Builder endsAt(NodeContract|string|integer $node)
 * @method static Builder between(NodeContract|string|integer $startNode, NodeContract|string|integer $endNode)
 * @method static Builder singleton(NodeContract|string|integer|null $node = null)
 * @method static Builder edge(EdgeContract|NodeContract|string|integer|null $edgeOrParent = null, NodeContract|string|integer|null $child = null)
 */
trait PathScopes
{

    /** @return DatabaseGraph */
    public abstract function getGraph();


    /**
     * Only gives the paths that start at the provided node.
     *
     * @param Builder $query
     * @param NodeContract|string|integer $node
     * @return Builder
     * @throws NodeNotFoundException
     */
    public function scopeStartsAt( $query, $node ) {
        $nodeId = $this->getGraph()->getNodeId($node);
        return $query->where('first_node_id','=',$nodeId);
    }

    /**
     * Only gives the paths that ends at the provided node.
     *
     * @param Builder $query
     * @param NodeContract|string|integer $node
     * @return Builder
     * @throws NodeNotFoundException
     */
    public function scopeEndsAt($query, $node ) {
        $nodeId = $this->getGraph()->getNodeId($node);
        return $query->where('last_node_id', '=', $nodeId);
    }

    /**
     * Only gives the paths from $startNode to $endNode.
     *
     * @param Builder $query
     * @param NodeContract|string|integer $startNode
     * @param NodeContract|string|integer $endNode
     * @return Builder
     * @throws NodeNotFoundException
     */
    public function scopeBetween($query, $startNode, $endNode) {
        $query = $this->scopeStartsAt($query, $startNode);
        $query = $this->scopeEndsAt($query, $endNode);
        return $query;
    }

    /**
     * Only gives the paths that are singletons. If the $node parameter is given, it only gives the path that
     * is the singleton path of that node.
     *
     * @param Builder $query
     * @param NodeContract|string|integer|null $node
     * @return Builder
     * @throws NodeNotFoundException
     */
    public function scopeSingleton($query, $node = null) {
        if($node !== null) {
            $query = $this->scopeStartsAt($query, $node);
        }
        return $query->where('size','=',1);
    }

    /**
     * Only gives the paths that represent edges.
     *
     * If the $edgeOrParent parameter is an instance of `\Roelhem\RbacGraph\Contracts\Edge`, Only the path that
     * represents that specific edge is given.
     *
     * If the $edgeOrParent parameter represents a node. Only the edges that have this node as its parent will
     * be given.
     *
     * If the $child parameter represents a node. Only the edges that have this node as its child will be given.
     *
     * @param Builder $query
     * @param EdgeContract|NodeContract|string|integer|null $edgeOrParent
     * @param NodeContract|string|integer|null $child
     * @return Builder
     * @throws NodeNotFoundException
     */
    public function scopeEdge($query, $edgeOrParent = null, $child = null) {
        if ($edgeOrParent instanceof EdgeContract) {
            $edge = $edgeOrParent;
            $parent = $edge->getParentId();
            $child = $edge->getChildId();
        } else {
            $parent = $edgeOrParent;
        }

        if($parent !== null) {
            $query = $this->scopeStartsAt($query, $parent);
        }

        if($child !== null) {
            $query = $this->scopeEndsAt($query, $parent);
        }

        return $query->where('size','=',2);
    }
}