<?php

namespace Roelhem\RbacGraph\Authorizers;

use Roelhem\RbacGraph\Contracts\Authorizable;
use Roelhem\RbacGraph\Contracts\AuthorizableGraph;
use Roelhem\RbacGraph\Contracts\Authorizer;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\PathFinder;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;
use Roelhem\RbacGraph\PathFinders\RecursivePathFinder;

class SimpleAuthorizer implements Authorizer
{

    /**
     * @var AuthorizableGraph
     */
    protected $graph;

    /**
     * Saves the authorizable object that works on this authorizable.
     *
     * @var Authorizable
     */
    protected $authorizable;

    /**
     * PathFinderAuthorizer constructor.
     *
     * @param Graph $graph
     * @param Authorizable $authorizable
     * @throws WrongGraphException
     */
    public function __construct(Graph $graph, Authorizable $authorizable)
    {
        if(!$graph->contains($authorizable)) {
            throw new WrongGraphException("The authorizable can't be authorized in the provided graph.");
        }
        $this->graph = $graph;
        $this->authorizable = $authorizable;
    }

    /**
     * Returns the authorizable graph on which this authorizer performs its authorization.
     *
     * @return AuthorizableGraph
     */
    public function getGraph()
    {
        return $this->graph;
    }

    /**
     * @inheritdoc
     */
    public function getAutorizable()
    {
        return $this->authorizable;
    }

    /**
     * Returns a new PathFinder instance for the provided path.
     *
     * @param Graph $graph
     * @return PathFinder
     */
    public function getPathFinder(Graph $graph) {
        return new RecursivePathFinder($graph);
    }

    /**
     * Authorizes the provided node and returns the verdict.
     *
     * @param Node|string|integer $node
     * @param array $attributes
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function authorize($node, $attributes)
    {
        $graph = $this->getGraph();
        $pathFinder = $this->getPathFinder($graph);

        $node = $graph->getNode($node);
        $entryNodes = $graph->getEntryNodes($this->getAutorizable());

        foreach ($entryNodes as $entryNode) {
            if($pathFinder->exists($entryNode, $node)) {
                return true;
            }
        }
        return false;
    }
}