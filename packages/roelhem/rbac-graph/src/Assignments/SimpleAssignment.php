<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 20:40
 */

namespace Roelhem\RbacGraph\Assignments;


use Roelhem\RbacGraph\Contracts\Assignable;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;

/**
 * Class SimpleAssignment
 *
 * A simple implementation of the Assignment contract using two protected properties.
 *
 * @package Roelhem\RbacGraph\Assignments
 */
class SimpleAssignment extends AbstractAssignment
{

    /**
     * @var Assignable
     */
    protected $assignable;

    /**
     * @var Node
     */
    protected $node;

    /**
     * SimpleAssignment constructor.
     *
     * @param Assignable $assignable
     * @param Node|string|integer $node
     * @throws WrongGraphException       Is thrown when the authorizable has a different graph as the node.
     * @throws NodeNotFoundException     It thrown when no node could be found.
     */
    public function __construct(Assignable $assignable, $node)
    {
        $this->assignable = $assignable;

        $graph = $this->assignable->getGraph();

        if($node instanceof Node) {
            if($graph->equals($node->getGraph())) {
                $this->node = $node;
            } else {
                throw new WrongGraphException('The authorizable object and the node belong to different graphs.');
            }
        }

        $this->node = $graph->getNode($node);
    }

    /**
     * Returns the graph of this assignment.
     *
     * @return Graph
     */
    public function getGraph()
    {
        return $this->assignable->getGraph();
    }

    /**
     * Returns the node that is assigned to an authorizable object.
     *
     * @return Node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * Returns the assignable object that have a node assigned to it.
     *
     * @return Assignable
     */
    public function getAssignable()
    {
        return $this->assignable;
    }
}