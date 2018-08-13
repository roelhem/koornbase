<?php

namespace Roelhem\RbacGraph\Graphs\Tools\Filters;

use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use TRex\Reflection\CallableReflection;

use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Traits\HasGraphProperty;

class CallbackNodeFilter extends AbstractNodeFilter
{

    use HasGraphProperty;


    public const NODE_TYPE_ORIGINAL = 0;
    public const NODE_TYPE_INSTANCE = 1;
    public const NODE_TYPE_ID       = 2;
    public const NODE_TYPE_NAME     = 3;

    /**
     * A callback that takes a node as its first argument and a graph as its second argument. It should return
     * a boolean value that determines if the node of the argument is passed by the filter.
     *
     * @var callable
     */
    protected $callback;

    /**
     * @var int
     */
    protected $nodeParamType = self::NODE_TYPE_ORIGINAL;

    /**
     * @var bool
     */
    protected $graphParam = false;

    /**
     * CallbackNodeFilter constructor.
     * @param Graph $graph
     * @param callable $callback
     */
    public function __construct($graph, callable $callback)
    {
        $this->initGraph($graph);
        $this->initCallback($callback);
    }

    /**
     * Initializes the filter using the callback.
     *
     * @param $callback
     */
    protected function initCallback($callback) {
        $this->callback = $callback;

        $reflection = new CallableReflection($callback);
        $reflector = $reflection->getReflector();
        $parameters = $reflector->getParameters();

        if(count($parameters) < 1) {
            throw new \InvalidArgumentException('The callback needs at least one parameter for the input node.');
        }


        $this->initNodeParameter($parameters[0]);

        if(count($parameters) > 1) {
            $this->graphParam = true;
        }

    }

    /**
     * Inspects the provided parameter and initializes the properties.
     *
     * @param \ReflectionParameter $parameter
     */
    protected function initNodeParameter(\ReflectionParameter $parameter) {
        if($parameter->hasType()) {
            $type = strval($parameter->getType());
            switch ($type) {
                case 'int':
                    $this->nodeParamType = self::NODE_TYPE_ID;
                    break;
                case 'string':
                    $this->nodeParamType = self::NODE_TYPE_NAME;
                    break;
                case Node::class:
                    $this->nodeParamType = self::NODE_TYPE_INSTANCE;
                    break;
                default:
                    if (is_subclass_of($type, Node::class)) {
                        $this->nodeParamType = self::NODE_TYPE_INSTANCE;
                    } else {
                        throw new \InvalidArgumentException("The node-argument type-hinting is not recognized '$type'.");
                    }
                    break;
            }
        } else {
            $this->nodeParamType = self::NODE_TYPE_ORIGINAL;
        }
    }

    /**
     * @param Node|string|integer $node
     * @return bool
     * @throws NodeNotFoundException
     */
    protected function getCallbackValue($node) {
        $callback = $this->callback;

        $nodeArgument = $this->getNodeArgument($node);

        if($this->graphParam) {
            return boolval($callback($nodeArgument, $this->getGraph()));
        } else {
            return boolval($callback($nodeArgument));
        }
    }

    /**
     * @param $node
     * @return int|Node|string
     * @throws NodeNotFoundException
     */
    protected function getNodeArgument($node) {
        switch ($this->nodeParamType) {
            case self::NODE_TYPE_ORIGINAL:
                return $node;
            case self::NODE_TYPE_INSTANCE:
                return $this->getGraph()->getNode($node);
            case self::NODE_TYPE_ID:
                return $this->getGraph()->getNodeId($node);
            case self::NODE_TYPE_NAME:
                return $this->getGraph()->getNodeName($node);
            default:
                throw new \LogicException('Invalid nodeParamType.');
        }
    }

    /**
     * Returns the param-type of the node parameter.
     *
     * @return int
     */
    public function getNodeParamType() {
        return $this->nodeParamType;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  IMPLEMENTATION: NodeFilter  -------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function includeNode($node)
    {
        return $this->getCallbackValue($node);
    }

    /**
     * @inheritdoc
     */
    public function filter($nodes)
    {
        if(!($nodes instanceof Collection)) {
            $nodes = collect($nodes);
        }

        return $nodes->filter([$this, 'includeNode']);
    }
}