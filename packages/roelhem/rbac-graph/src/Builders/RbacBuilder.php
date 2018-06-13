<?php

namespace Roelhem\RbacGraph\Builders;

use Roelhem\RbacGraph\Contracts\Builder as BuilderContract;
use Roelhem\RbacGraph\Contracts\NodeBuilder as NodeBuilderContract;
use Roelhem\RbacGraph\Contracts\Traits\HasEdgeArray;
use Roelhem\RbacGraph\Contracts\Traits\HasIdSequenceGenerator;
use Roelhem\RbacGraph\Contracts\Traits\HasNodeDictionaries;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\EdgeNotAllowedException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

class RbacBuilder implements BuilderContract
{

    use HasNodeDictionaries;
    use HasEdgeArray;
    use HasIdSequenceGenerator;

    protected $prefixes = [];

    /**
     * @inheritdoc
     */
    public function equals( $other ) : bool {
        return $this === $other;
    }

    /**
     * Returns the current name prefix or the prefix till the provided depth.
     *
     * @param integer|null $depth
     * @return string
     */
    protected function getPrefix($depth = null) {
        if($depth === null) {
            $prefixes = $this->prefixes;
        } else {
            $prefixes = array_slice($this->prefixes, 0, intval($depth));
        }
        return implode('', $prefixes);
    }

    /**
     * @inheritdoc
     */
    public function find(string $name)
    {
        for($i = 0; $i <= count($this->prefixes); $i++) {
            $searchName = $this->getPrefix($i).$name;
            if($this->hasNodeName($searchName)) {
                return $this->getNodeByName($searchName);
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function get(string $name)
    {
        $builder = $this->find($name);
        if($builder !== null) {
            return $builder;
        }
        $prefixes = '[\'' . implode('\', \'', $this->prefixes) . '\']';
        throw new NodeNotFoundException("Couldn't get a NodeBuilder node from the string '$name'. Current prefixes: $prefixes.");
    }

    /**
     * @inheritdoc
     */
    public function create($type, string $name) {

        $prefix = $this->getPrefix();
        $builder = new NodeBuilder($this, $type, $prefix.$name, $this->getNextId());

        $this->storeNode($builder);

        return $builder;
    }

    /**
     * @inheritdoc
     */
    public function node($type, string $name)
    {
        $type = NodeType::get($type);
        $builder = $this->find($name);
        if(($builder instanceof NodeBuilderContract) && $builder->getType() === $type) {
            return $builder;
        } else {
            return $this->create($type, $name);
        }
    }

    /**
     * @inheritdoc
     */
    public function role(string $name)
    {
        return $this->node(NodeType::ROLE(), $name);
    }

    /**
     * @inheritdoc
     */
    public function permission(string $name)
    {
        return $this->node(NodeType::PERMISSION(), $name);
    }

    /**
     * @inheritdoc
     */
    public function group(string $prefix, callable $definitions)
    {
        array_push($this->prefixes, $prefix);
        $definitions($this);
        array_pop($this->prefixes);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function edge($parent, $child)
    {
        if ($this->hasEdge($parent, $child)) {
            return $this->getEdge($parent, $child);
        } else {
            $parent = $this->getNode($parent);
            $child = $this->getNode($child);
            if($parent->getType()->allowChildNode($child)) {
                $edge = new EdgeBuilder($this, $parent, $child);
                $this->storeEdge($edge);
                return $edge;
            } else {
                $parentTypeName = $parent->getType()->getName();
                $childTypeName = $child->getType()->getName();
                throw new EdgeNotAllowedException("A node of type $parentTypeName can't have a node of type $childTypeName as a child.");
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function build()
    {
        return $this;
    }


}