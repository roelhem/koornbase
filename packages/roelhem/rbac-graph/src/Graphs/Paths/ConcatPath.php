<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 17:06
 */

namespace Roelhem\RbacGraph\Graphs\Paths;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\Path;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\PathEmptyException;
use Roelhem\RbacGraph\Exceptions\PathIndexException;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasNoAssignments;

class ConcatPath implements Path
{

    use HasNoAssignments;

    /**
     * @var Path
     */
    protected $firstPath;

    /**
     * @var Path
     */
    protected $secondPath;

    /**
     * @var Edge
     */
    protected $edge;

    /**
     * ConcatPath constructor.
     * @param Path $firstPath
     * @param Path $secondPath
     * @inheritdoc
     */
    public function __construct($firstPath, $secondPath)
    {
        // Check the argument types.
        if(!($firstPath instanceof Path)) {
            throw new \InvalidArgumentException("The argument of the first path has to be an instance of Path.");
        }
        if(!($secondPath instanceof Path)) {
            throw new \InvalidArgumentException("The argument of the second path has to be an instance of Path.");
        }

        // Retrieve the graph and check the compatibility.
        $graph = $firstPath->getGraph();
        if(!$graph->contains($secondPath)) {
            throw new WrongGraphException("The provided paths have different graphs.");
        }

        // Get the edge to connect the two parts.
        $parent = $firstPath->getLastNode();
        $child = $secondPath->getFirstNode();
        $edge = $graph->getEdge($parent, $child);

        // Store the values.
        $this->firstPath = $firstPath;
        $this->secondPath = $secondPath;
        $this->edge = $edge;
    }

    /**
     * Gives the graph where this object belongs to.
     *
     * @return Graph
     */
    public function getGraph()
    {
        return $this->firstPath->getGraph();
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return $this->firstPath->count() + $this->secondPath->count();
    }

    /**
     * Returns if the provided argument is contained in this graph.
     *
     * @param mixed $other
     * @return boolean
     */
    public function contains($other)
    {
        return $this->firstPath->contains($other) || $this->secondPath->contains($other) || $other === $this->edge;
    }

    /**
     * Returns a collection of all the nodes in this graph.
     *
     * @return Collection|Node[]
     */
    public function getNodes()
    {
        return $this->firstPath->getNodes()->merge($this->secondPath->getNodes());
    }

    /**
     * Returns a collection of all the edges in this graph.
     *
     * @return Collection|Edge[]
     */
    public function getEdges()
    {
        return collect([
            $this->firstPath->getEdges(),
            $this->edge,
            $this->secondPath->getEdges()
        ])->flatten();
    }

    /**
     * Returns whether or not this graph has a node with the provided $id.
     *
     * @param integer $id
     * @return boolean
     */
    public function hasNodeId($id)
    {
        return $this->firstPath->hasNodeId($id) || $this->secondPath->hasNodeId($id);
    }

    /**
     * Returns whether or not this graph has a node with the provided $name.
     *
     * @param string $name
     * @return boolean
     */
    public function hasNodeName($name)
    {
        return $this->firstPath->hasNodeName($name) || $this->secondPath->hasNodeName($name);
    }

    /**
     * Returns whether or not this graph has a node from the provided $node parameter.
     *
     * @param Node|string|integer $node An instance, name or id of the searched node.
     * @return boolean
     */
    public function hasNode($node)
    {
        return $this->firstPath->hasNode($node) || $this->secondPath->hasNode($node);
    }

    /**
     * Returns the node with the provided id.
     *
     * @param integer $id
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeById($id)
    {
        try {
            return $this->firstPath->getNodeById($id);
        } catch (NodeNotFoundException $nodeNotFoundException) {
            return $this->secondPath->getNodeById($id);
        }
    }

    /**
     * Returns the node with the provided name.
     *
     * @param string $name
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeByName($name)
    {
        try {
            return $this->firstPath->getNodeByName($name);
        } catch (NodeNotFoundException $nodeNotFoundException) {
            return $this->secondPath->getNodeByName($name);
        }
    }

    /**
     * Returns the node based on the given $node parameter.
     *
     * @param Node|string|integer $node An instance, name or id of the searched node.
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNode($node)
    {
        try {
            return $this->firstPath->getNode($node);
        } catch (NodeNotFoundException $nodeNotFoundException) {
            return $this->secondPath->getNode($node);
        }
    }

    /**
     * Returns the id of the node that was referenced by the $node parameter.
     *
     * @param Node|string|integer $node An instance, name or id of the searched node.
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeId($node)
    {
        try {
            return $this->firstPath->getNodeId($node);
        } catch (NodeNotFoundException $nodeNotFoundException) {
            return $this->secondPath->getNodeId($node);
        }
    }

    /**
     * Returns the name of the node that was referenced by the $node parameter.
     *
     * @param Node|string|integer $node An instance, name or id of the searched node.
     * @return string
     * @throws NodeNotFoundException
     */
    public function getNodeName($node)
    {
        try {
            return $this->firstPath->getNodeName($node);
        } catch (NodeNotFoundException $nodeNotFoundException) {
            return $this->secondPath->getNodeName($node);
        }
    }

    /**
     * Returns if the two provided nodes are equal to each other.
     *
     * @param Node|string|integer $nodeA
     * @param Node|string|integer $nodeB
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function nodeEquals($nodeA, $nodeB)
    {
        return $this->getGraph()->nodeEquals($nodeA, $nodeB);
    }

    /**
     * Returns whether or not this graph has a edge between the nodes $parent and $child.
     *
     * @param Node|string|integer $parent An instance, name or id of the searched node.
     * @param Node|string|integer $child An instance, name or id of the searched node.
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function hasEdge($parent, $child)
    {
        return $this->firstPath->hasEdge($parent, $child) ||
            $this->secondPath->hasEdge($parent, $child) ||
            ($this->nodeEquals($parent, $this->edge->getParent()) && $this->nodeEquals($child, $this->edge->getChild()));
    }

    /**
     * Returns the edge between the nodes $from and $to.
     *
     * @param Node|string|integer $parent An instance, name or id of the searched node.
     * @param Node|string|integer $child An instance, name or id of the searched node.
     * @return Edge
     * @throws EdgeNotFoundException
     * @throws NodeNotFoundException
     */
    public function getEdge($parent, $child)
    {
        try {
            return $this->firstPath->getEdge($parent, $child);
        } catch (EdgeNotFoundException|NodeNotFoundException $exception) {
            try {
                return $this->secondPath->getEdge($parent, $child);
            } catch (EdgeNotFoundException|NodeNotFoundException $exception) {
                if($this->nodeEquals($parent, $this->edge->getParent()) && $this->nodeEquals($child, $this->edge->getChild())) {
                    return $this->edge;
                } else {
                    throw new EdgeNotFoundException("Can't find the edge between the provided nodes.");
                }
            }
        }
    }

    /**
     * Returns all the outgoing edges of a specific node
     *
     * @param Node|string|integer $node An instance, name or id of the searched node.
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     * @throws PathEmptyException
     */
    public function getOutgoingEdges($node)
    {
        $outgoingEdge = $this->getOutgoingEdge($node);
        if($outgoingEdge !== null) {
            return collect([$outgoingEdge]);
        } else {
            return collect([]);
        }
    }

    /**
     * Returns all the incoming edges of a specific node
     *
     * @param Node|string|integer $node An instance, name or id of the searched node.
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     * @throws PathEmptyException
     */
    public function getIncomingEdges($node)
    {
        $incomingEdge = $this->getIncomingEdge($node);
        if($incomingEdge !== null) {
            return collect([$incomingEdge]);
        } else {
            return collect([]);
        }
    }

    /**
     * Returns all the children of a specific node
     *
     * @param $node
     * @return Collection|Node[]
     * @throws NodeNotFoundException
     * @throws PathEmptyException
     */
    public function getChildren($node)
    {
        $nextNode = $this->getNextNode($node);
        if($nextNode !== null) {
            return collect([$nextNode]);
        } else {
            return collect([]);
        }
    }

    /**
     * Returns all the parents of a specific node
     *
     * @param $node
     * @return Collection|Node[]
     * @throws NodeNotFoundException
     * @throws PathEmptyException
     */
    public function getParents($node)
    {
        $prevNode = $this->getPrevNode($node);
        if($prevNode !== null) {
            return collect([$prevNode]);
        } else {
            return collect([]);
        }
    }

    /**
     * Returns the node at the given index
     *
     * @param integer $index
     * @return Node
     * @throws PathIndexException
     */
    public function getNodeAt($index)
    {
        if($index < $this->firstPath->count()) {
            return $this->firstPath->getNodeAt($index);
        } else {
            return $this->secondPath->getNodeAt($index - $this->firstPath->count());
        }
    }

    /**
     * Returns the first node of the path.
     *
     * @return Node
     * @throws PathEmptyException
     */
    public function getFirstNode()
    {
        return $this->firstPath->getFirstNode();
    }

    /**
     * Returns the last node of the path.
     *
     * @return Node
     * @throws PathEmptyException
     */
    public function getLastNode()
    {
        return $this->secondPath->getLastNode();
    }

    /**
     * Returns the index of the node with the provided id in this path.
     *
     * @param  integer $id
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeIndexById($id)
    {
        try {
            return $this->firstPath->getNodeIndexById($id);
        } catch (NodeNotFoundException $exception) {
            return $this->secondPath->getNodeIndexById($id) + $this->firstPath->count();
        }
    }

    /**
     * Returns the index of the node with the provided name in this path.
     *
     * @param  string $name
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeIndexByName($name)
    {
        try {
            return $this->firstPath->getNodeIndexByName($name);
        } catch (NodeNotFoundException $exception) {
            return $this->secondPath->getNodeIndexByName($name) + $this->firstPath->count();
        }
    }

    /**
     * Returns the index of the node in this path.
     *
     * @param  Node|string|integer $node
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeIndex($node)
    {
        try {
            return $this->firstPath->getNodeIndex($node);
        } catch (NodeNotFoundException $exception) {
            return $this->secondPath->getNodeIndex($node) + $this->firstPath->count();
        }
    }

    /**
     * Returns the Node that comes after the provided Node. Returns null if the provided node is the last Node.
     *
     * @param  Node|string|integer $node
     * @return Node|null
     * @throws NodeNotFoundException
     * @throws PathEmptyException
     */
    public function getNextNode($node)
    {
        try {
            $nextNode = $this->firstPath->getNextNode($node);
            if($nextNode !== null) {
                return $nextNode;
            } else {
                return $this->secondPath->getFirstNode();
            }
        } catch (NodeNotFoundException $exception) {
            return $this->secondPath->getNextNode($node);
        }
    }

    /**
     * Returns the Node that came before the provided node. Returns null if the provided node is the first Node.
     *
     * @param Node|string|integer $node
     * @return Node|null
     * @throws PathEmptyException
     */
    public function getPrevNode($node)
    {
        try {
            return $this->firstPath->getPrevNode($node);
        } catch (NodeNotFoundException $exception) {
            $prevNode = $this->secondPath->getPrevNode($node);
            if($prevNode !== null) {
                return $prevNode;
            } else {
                return $this->firstPath->getLastNode();
            }
        }
    }

    /**
     * Returns the nodes in this path in the right order.
     *
     * (The keys of the path are the index values of the node in the path.)
     *
     * @return Node[]
     */
    public function getNodeList()
    {
        return array_merge($this->firstPath->getNodeList(), $this->secondPath->getNodeList());
    }

    /**
     * Returns the outgoing edge of the node with the provided index.
     *
     * @param $index
     * @return Edge
     * @throws PathIndexException
     */
    public function getEdgeAt($index)
    {
        if($index < $this->firstPath->count() - 1) {
            return $this->firstPath->getEdgeAt($index);
        } elseif($index === $this->firstPath->count() - 1) {
            return $this->edge;
        } else {
            return $this->secondPath->getEdgeAt($index - $this->firstPath->count());
        }
    }

    /**
     * Returns the outgoing edge of the provided node. Returns null if the provided node was the last node of the path.
     *
     * @param Node|string|integer $node
     * @return Edge|null
     * @throws NodeNotFoundException
     * @throws PathEmptyException
     */
    public function getOutgoingEdge($node)
    {
        if($this->nodeEquals($node, $this->firstPath->getLastNode())) {
            return $this->edge;
        }

        try {
            return $this->firstPath->getOutgoingEdge($node);
        } catch (NodeNotFoundException $exception) {
            return $this->secondPath->getOutgoingEdge($node);
        }
    }

    /**
     * Returns the incoming edge of the provided node. Returns null if the provided node was the first node of the path.
     *
     * @param Node|string|integer $node
     * @return Edge|null
     * @throws NodeNotFoundException
     * @throws PathEmptyException
     */
    public function getIncomingEdge($node)
    {
        if($this->nodeEquals($node, $this->secondPath->getFirstNode())) {
            return $this->edge;
        }

        try {
            return $this->firstPath->getIncomingEdge($node);
        } catch (NodeNotFoundException $exception) {
            return $this->secondPath->getIncomingEdge($node);
        }
    }

    /**
     * Returns a list of the edges in this path in the right order.
     *
     * @return Edge[]
     */
    public function getEdgeList()
    {
        return array_merge($this->firstPath->getEdgeList(), [$this->edge], $this->secondPath->getEdgeList());
    }

    /**
     * Returns if the $node (that exists in the super-graph) also exists in the sub-graph.
     *
     * @param Node|string|integer $node
     * @return boolean
     */
    public function containsNode($node)
    {
        return $this->firstPath->containsNode($node) || $this->secondPath->containsNode($node);
    }

    /**
     * Returns if the $edge (that exists in the super-graph) also exists in the sub-graph.
     *
     * @param Edge $edge
     * @return boolean
     */
    public function containsEdge($edge)
    {
        return ($edge->getParentId() === $this->edge->getParentId() && $edge->getChildId() === $this->edge->getChildId()) ||
            $this->firstPath->containsEdge($edge) ||
            $this->secondPath->containsEdge($edge);
    }
}