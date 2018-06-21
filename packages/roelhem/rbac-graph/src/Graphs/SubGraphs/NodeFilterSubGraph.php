<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-06-18
 * Time: 20:28
 */

namespace Roelhem\RbacGraph\Graphs\SubGraphs;


use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\NodeFilter;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultEquals;
use Roelhem\RbacGraph\Exceptions\RbacGraphException;
use Roelhem\RbacGraph\Filters\CallbackNodeFilter;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasInducedEdges;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasContainsNodeMethod;

class NodeFilterSubGraph extends AbstractSubGraph
{

    use HasInducedEdges;
    use HasContainsNodeMethod;
    use GraphDefaultEquals;

    /**
     * @var NodeFilter
     */
    protected $filter;

    /**
     * NodeFilterSubGraph constructor.
     * @param Graph $graph
     * @param NodeFilter|callable $filter
     */
    public function __construct( $graph, $filter )
    {
        $this->initGraph($graph);

        if($filter instanceof NodeFilter) {
            $this->filter = $filter;
        } elseif(is_callable($filter)) {
            try {
                $this->filter = new CallbackNodeFilter($graph, $filter);
            } catch (RbacGraphException $graphException) {
                throw new \InvalidArgumentException('Can\'t use the provided callback as a filter', 0, $graphException);
            }
        } else {
            throw new \InvalidArgumentException("The filter parameter has to be a NodeFilter of valid callable.");
        }
    }

    /**
     * @return NodeFilter
     */
    public function getNodeFilter()
    {
        return $this->filter;
    }

    /**
     * @param Node|string|integer $node
     * @return bool
     */
    public function containsNode($node)
    {
        return $this->filter->includeNode($node);
    }

    /**
     * @inheritdoc
     */
    public function getNodes()
    {
        return $this->filter->filter($this->getGraph()->getNodes());
    }

    /**
     * @inheritdoc
     */
    public function getChildren($node)
    {
        return $this->filter->filter($this->getGraph()->getChildren($node));
    }

    /**
     * @inheritdoc
     */
    public function getParents($node)
    {
        return $this->filter->filter($this->getGraph()->getParents($node));
    }

}