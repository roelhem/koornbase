<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 02:01
 */

namespace Roelhem\RbacGraph\Contracts\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\EdgeNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;

trait HasEdgeDictionaries
{

    use HasEdgeArray;

    protected $edgeChildToParent;

    /**
     * Stores an edge in the dictionary structure.
     *
     * @param Edge $edge
     * @throws EdgeNotUniqueException
     * @throws WrongGraphException
     */
    protected function storeEdge( Edge $edge ) {
        if($this->contains($edge)) {

            $parentId = $edge->getParentId();
            $childId = $edge->getChildId();

            if ($this->hasEdge($parentId, $childId)) {
                throw new EdgeNotUniqueException;
            }

            if(!array_key_exists($parentId, $this->edges) || !is_array($this->edges[$parentId])) {
                $this->edges[$parentId] = [];
            }
            $this->edges[$parentId][$childId] = $edge;


            if(!array_key_exists($childId, $this->edgeChildToParent) || !is_array($this->edges[$childId])) {
                $this->edgeChildToParent[$childId] = [];
            }
            $this->edgeChildToParent[$childId][] = $parentId;
        } else {
            throw new WrongGraphException;
        }
    }

    /**
     * @param $parent
     * @param $child
     * @return Edge
     * @throws EdgeNotFoundException
     * @throws NodeNotFoundException
     */
    public function getEdge($parent, $child)
    {
        $parentId = $this->getNodeId($parent);
        if(array_key_exists($parentId, $this->edges)) {
            $outgoingEdges = $this->edges[$parentId];

            if(!is_array($outgoingEdges)) {
                $parentName = $this->getNodeName($parent);
                throw new EdgeNotFoundException("Can't find any edges with the node '$parentName' on the parent side. The key '$parentId' exists in the edges array, but the value wasn't an array.");
            }

            $childId = $this->getNodeId($child);

            if(!array_key_exists($childId, $outgoingEdges)) {
                $parentName = $this->getNodeName($parent);
                $childName = $this->getNodeName($child);
                throw new EdgeNotFoundException("Can't find an edge from the node '$parentName' to the node '$childName'.");
            }

            $edge = $outgoingEdges[$childId];

            if (!($edge instanceof Edge)) {
                $parentName = $this->getNodeName($parent);
                $childName = $this->getNodeName($child);
                throw new EdgeNotFoundException("Can't find an edge from the node '$parentName' to the node '$childName'. The keys [$parentId][$childId] exists in the edge array, but the value isn't an instance of Edge.");
            }

            return $edge;
        } else {
            $parentName = $this->getNodeName($parent);
            throw new EdgeNotFoundException("Can't find any edges with the node '$parentName' on the parent side.");
        }
    }

    /**
     * @param Node|string|integer $node
     * @return Collection
     */
    public function getOutgoingEdges($node)
    {
        $nodeId = $this->getNodeId($node);
        if(array_key_exists($nodeId, $this->edges)) {
            $outgoingEdges = $this->edges[$nodeId];
            return collect($outgoingEdges)->filter(function($edge) {
                return $edge instanceof Edge;
            })->values();
        } else {
            return collect([]);
        }
    }

    /**
     * @param Node|string|integer $node
     * @return Collection
     */
    public function getIncomingEdges($node)
    {
        $nodeId = $this->getNodeId($node);
        if(array_key_exists($nodeId, $this->edgeChildToParent)) {
            $parentIds = $this->edgeChildToParent[$nodeId];
            return collect($parentIds)->filter(function($parentId) use ($nodeId) {
                return
                    array_key_exists($parentId, $this->edges) &&
                    array_key_exists($nodeId, $this->edges[$parentId]) &&
                    $this->edges[$parentId][$nodeId] instanceof Edge;
            })->map(function($parentId) use ($nodeId) {
                return $this->edges[$parentId][$nodeId];
            })->values();
        } else {
            return collect([]);
        }
    }

}