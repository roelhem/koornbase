<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 19:57
 */

namespace Roelhem\RbacGraph\Database\Traits\Node;

use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node as NodeContract;
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Database\Edge;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Traits\BelongsToDatabaseGraph;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;


trait NodeContractImplementation
{

    use BelongsToDatabaseGraph;

    /**
     * Returns the unique identifier integer for this node in the graph.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the string that uniquely identifies this node in the graph.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the type of this NodeContract. The value is a value in the NodeTypes enum.
     *
     * @return NodeType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the title of this node.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the description of this node.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns a collection of all the incoming edges of this node.
     *
     * @return Collection|Edge[]
     */
    public function getIncomingEdges()
    {
        return $this->incomingEdges;
    }

    /**
     * Returns a collection of all the parent nodes of this node.
     *
     * @return Collection|NodeContract
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Returns the edge to this node which is from the provided node.
     *
     * @param NodeContract|string|integer $node An instance, name or id of the provided node.
     * @return Edge
     * @throws NodeNotFoundException
     * @throws EdgeNotFoundException
     */
    public function getEdgeFrom($node)
    {
        $parent = $this->parents()->node($node)->first();
        if($parent instanceof Node && $parent->edge instanceof Edge) {
            return $parent->edge;
        }

        $parent_name = $this->getGraph()->getNodeName($node);
        throw new EdgeNotFoundException("Can't find an edge from node '$parent_name' to the current node '{$this->name}'.");
    }

    /**
     * Returns if there is an edge to this array from the provided node.
     *
     * @param NodeContract|string|integer $node An instance, name or id of the provided node.
     * @return boolean
     */
    public function hasEdgeFrom($node)
    {
        return $this->parents()->node($node)->exists();
    }

    /**
     * Returns a collection of all the outgoing edges of this node.
     *
     * @return Collection|Edge[]
     */
    public function getOutgoingEdges()
    {
        return $this->outgoingEdges;
    }

    /**
     * Returns a collection of all the child nodes of this node.
     *
     * @return Collection|NodeContract
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Returns the edge from this node to the provided node.
     *
     * @param NodeContract|string|integer $node An instance, name or id of the provided node.
     * @return Edge
     * @throws NodeNotFoundException
     * @throws EdgeNotFoundException
     */
    public function getEdgeTo($node)
    {
        $child = $this->children()->node($node)->first();
        if($child instanceof Node && $child->edge instanceof Edge) {
            return $child->edge;
        }

        $child_name = $this->getGraph()->getNodeName($node);
        throw new EdgeNotFoundException("Can't find an edge from the current node '{$this->name}' to the node '{$child_name}'.");
    }

    /**
     * Returns if there exists an edge from this node to the provided node.
     *
     * @param NodeContract|string|integer $node An instance, name or id of the provided node.
     * @return boolean
     */
    public function hasEdgeTo($node)
    {
        return $this->children()->node($node)->exists();
    }

    /**
     * Sets the title of this node to the provided $title.
     *
     * @param string|null $title
     * @return void
     */
    public function setTitle($title)
    {
        if($title === null) {
            $this->title = null;
        } else {
            $this->title = strval($title);
        }
        $this->save();
    }

    /**
     * Sets the description of this node to the provided $description.
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription($description)
    {
        if($description === null) {
            $this->description = null;
        } else {
            $this->description = strval($description);
        }
        $this->save();
    }
}