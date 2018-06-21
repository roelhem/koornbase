<?php

namespace Roelhem\RbacGraph\Filters;

use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use TRex\Reflection\CallableReflection;

use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\NodeFilter;
use Roelhem\RbacGraph\Exceptions\RbacGraphException;
use Roelhem\RbacGraph\Traits\HasGraphProperty;
use TRex\Reflection\TypeReflection;

class CallbackNodeFilter extends AbstractNodeFilter
{

    use HasGraphProperty;


    protected const NODE_TYPE_ORIGINAL = 0;
    protected const NODE_TYPE_INSTANCE = 1;
    protected const NODE_TYPE_ID       = 2;
    protected const NODE_TYPE_NAME     = 3;

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
     *
     * @throws RbacGraphException
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
            $type = new TypeReflection($parameter->getType());
            if($type->isInteger()) {
                $this->nodeParamType = self::NODE_TYPE_ID;
            } elseif($type->isString()) {
                $this->nodeParamType = self::NODE_TYPE_NAME;
            } elseif($type->isObject()) {
                $this->nodeParamType = self::NODE_TYPE_INSTANCE;
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