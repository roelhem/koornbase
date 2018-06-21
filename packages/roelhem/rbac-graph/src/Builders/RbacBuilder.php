<?php

namespace Roelhem\RbacGraph\Builders;

use Roelhem\RbacGraph\Builders\Traits\ImplementBuilderShortcuts;
use Roelhem\RbacGraph\Contracts\Builder as BuilderContract;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\MutableGraph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\NodeBuilder as NodeBuilderContract;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Helpers\NamePrefixStack;

class RbacBuilder implements BuilderContract
{

    use ImplementBuilderShortcuts;

    /**
     * @var MutableGraph
     */
    protected $graph;

    /**
     * @var NamePrefixStack
     */
    protected $prefix;

    /**
     * RbacBuilder constructor.
     * @param MutableGraph $graph
     */
    public function __construct(MutableGraph $graph)
    {
        $this->graph = $graph;
        $this->prefix = new NamePrefixStack($graph);
    }

    /**
     * @return MutableGraph
     */
    public function getGraph()
    {
        return $this->graph;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  NODE BUILDER GETTERS/FINDERS  ------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function get(string $name)
    {
        $name = $this->prefix->name($name);
        $node = $this->getGraph()->getNodeByName($name);
        $nodeBuilder = new NodeBuilder($this, $node);
        return $nodeBuilder;
    }

    /**
     * @inheritdoc
     */
    public function find(string $name)
    {
        try {
            return $this->get($name);
        } catch (NodeNotFoundException $exception) {
            return null;
        }
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // --------  NODE BUILDER CREATORS  ------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function create($type, string $name, $options = []) {

        $name = $this->prefix->prefix($name);
        $node = $this->getGraph()->createNode($type, $name, $options);
        $nodeBuilder = new NodeBuilder($this, $node);

        return $nodeBuilder;
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // --------  NODE BUILDER INITIALISATION HELPERS  ----------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function node($type, string $name, $options = [])
    {
        $type = NodeType::get($type);
        $nodeBuilder = $this->find($name);
        if(($nodeBuilder instanceof NodeBuilderContract) && $nodeBuilder->getNode()->getType() === $type) {
            return $nodeBuilder;
        } else {
            return $this->create($type, $name, $options);
        }
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // --------  ASSIGNMENT BUILDER  ---------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @param NodeBuilderContract|Node|string|integer $input
     * @return Node
     * @throws NodeNotFoundException
     */
    protected function parseNode($input) {
        if(is_string($input)) {
            $name = $this->prefix->name($input);
        } elseif($input instanceof Node) {
            $name = $input->getName();
        } elseif($input instanceof NodeBuilderContract) {
            $name = $input->getNode()->getName();
        } else {
            $name = $this->getGraph()->getNodeName($input);
        }

        return $this->getGraph()->getNodeByName($name);
    }

    /**
     * @param NodeBuilderContract|Node|string|integer $parent
     * @param NodeBuilderContract|Node|string|integer $child
     * @throws NodeNotFoundException
     * @return Edge
     */
    public function createEdge($parent, $child) {
        $parent = $this->parseNode($parent);
        $child = $this->parseNode($child);

        return $this->getGraph()->createEdge($parent, $child);
    }

    /**
     * @param NodeBuilderContract|Node|string|integer $parent
     * @param NodeBuilderContract|Node|string|integer $child
     * @throws NodeNotFoundException
     * @throws EdgeNotFoundException
     * @return Edge
     */
    public function getEdge($parent, $child) {
        $parent = $this->parseNode($parent);
        $child = $this->parseNode($child);

        return $this->getGraph()->getEdge($parent, $child);
    }

    /**
     * @param NodeBuilderContract|Node|string|integer $parent
     * @param NodeBuilderContract|Node|string|integer $child
     * @return bool
     * @throws NodeNotFoundException
     */
    public function hasEdge($parent, $child) {
        $parent = $this->parseNode($parent);
        $child = $this->parseNode($child);

        return $this->getGraph()->hasEdge($parent, $child);
    }

    /**
     * @inheritdoc
     */
    public function edge($parent, $child) {
        if($this->hasEdge($parent, $child)) {
            return $this->getEdge($parent, $child);
        } else {
            return $this->createEdge($parent, $child);
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  GROUP BUILDER  --------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * @inheritdoc
     */
    public function group(string $prefix, callable $definitions)
    {
        $this->prefix->push($prefix);
        $definitions($this);
        $this->prefix->pop();
        return $this;
    }


}