<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 18:44
 */

namespace Roelhem\RbacGraph\Graphs\Paths;


use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\Path;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\PathIndexException;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasNoAssignments;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasSuperGraph;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\SubGraphDefaultContains;

class SingletonPath implements Path
{

    use HasNoAssignments;
    use HasSuperGraph;
    use SubGraphDefaultContains;

    /**
     * @var Node
     */
    protected $node;

    /**
     * SingletonPath constructor.
     * @param Graph $graph
     * @param Node|string|integer $node
     * @throws NodeNotFoundException
     */
    public function __construct(Graph $graph, $node)
    {
        $this->initGraph($graph);
        $this->node = $this->getGraph()->getNode($node);
    }

    /** @inheritdoc */
    public function count()
    {
        return 1;
    }

    /** @inheritdoc */
    public function getNodes()
    {
        return collect([$this->node]);
    }

    /** @inheritdoc */
    public function getEdges()
    {
        return collect([]);
    }

    /** @inheritdoc */
    public function hasNodeId($id)
    {
        return $this->node->getId() === $id;
    }

    /** @inheritdoc */
    public function hasNodeName($name)
    {
        return $this->node->getName() === $name;
    }

    /** @inheritdoc */
    public function hasNode($node)
    {
        if(is_integer($node)) {
            return $this->hasNodeId($node);
        } elseif(is_string($node)) {
            return $this->hasNodeName($node);
        } elseif($node instanceof Node) {
            return $this->hasNodeId($node->getId());
        } else {
            return false;
        }
    }

    /** @inheritdoc */
    public function getNodeById($id)
    {
        if($id === $this->node->getId()) {
            return $this->node;
        } else {
            throw new NodeNotFoundException("There doesn't exist a node in this path with the id $id.");
        }
    }

    /** @inheritdoc */
    public function getNodeByName($name)
    {
        if($name === $this->node->getName()) {
            return $this->node;
        } else {
            throw new NodeNotFoundException("There doesn't exist a node in this path with the name $name.");
        }
    }

    /** @inheritdoc */
    public function getNode($node)
    {
        if(is_integer($node)) {
            return $this->getNodeById($node);
        } elseif(is_string($node)) {
            return $this->getNodeByName($node);
        } elseif($node instanceof Node) {
            return $this->getNodeById($node);
        } else {
            throw new NodeNotFoundException("Can't find a node based on the provided node argument.");
        }
    }

    /** @inheritdoc */
    public function nodeEquals($nodeA, $nodeB)
    {
        $this->getGraph()->nodeEquals($nodeA, $nodeB);
    }

    /** @inheritdoc */
    public function hasEdge($parent, $child)
    {
        return false;
    }

    /** @inheritdoc */
    public function getEdge($parent, $child)
    {
        throw new EdgeNotFoundException('A SingletonPath can never have any edges.');
    }

    /** @inheritdoc */
    public function getOutgoingEdges($node)
    {
        return collect([]);
    }

    /** @inheritdoc */
    public function getIncomingEdges($node)
    {
        return collect([]);
    }

    /** @inheritdoc */
    public function getChildren($node)
    {
        return collect([]);
    }

    /** @inheritdoc */
    public function getParents($node)
    {
        return collect([]);
    }

    /** @inheritdoc */
    public function getNodeAt($index)
    {
        if($index === 0) {
            return $this->node;
        } else {
            throw new PathIndexException("A SingletonPath only has the index 0.");
        }
    }

    /** @inheritdoc */
    public function getFirstNode()
    {
        return $this->node;
    }

    /** @inheritdoc */
    public function getLastNode()
    {
        return $this->node;
    }

    /** @inheritdoc */
    public function getNodeIndexById($id)
    {
        if($this->hasNodeId($id)) {
            return 0;
        } else {
            throw new NodeNotFoundException("Can't find a node with the id $id in this path.");
        }
    }

    /** @inheritdoc */
    public function getNodeIndexByName($name)
    {
        if($this->hasNodeName($name)) {
            return 0;
        } else {
            throw new NodeNotFoundException("Can't find a node with the name $name in this path.");
        }
    }

    /** @inheritdoc */
    public function getNodeIndex($node)
    {
        if($this->hasNode($node)) {
            return 0;
        } else {
            throw new NodeNotFoundException("Can't find the provided node in this path.");
        }
    }

    /** @inheritdoc */
    public function getNextNode($node)
    {
        return null;
    }

    /** @inheritdoc */
    public function getPrevNode($node)
    {
        return null;
    }

    /** @inheritdoc */
    public function getNodeList()
    {
        return [$this->node];
    }

    /** @inheritdoc */
    public function getEdgeAt($index)
    {
        throw new PathIndexException("There are no edges in a SingletonPath.");
    }

    /** @inheritdoc */
    public function getOutgoingEdge($node)
    {
        return null;
    }

    /** @inheritdoc */
    public function getIncomingEdge($node)
    {
        return null;
    }

    /** @inheritdoc */
    public function getEdgeList()
    {
        return [];
    }

    /** @inheritdoc */
    public function containsNode($node)
    {
        return $this->hasNodeName($node);
    }

    /** @inheritdoc */
    public function containsEdge($edge)
    {
        return false;
    }
}