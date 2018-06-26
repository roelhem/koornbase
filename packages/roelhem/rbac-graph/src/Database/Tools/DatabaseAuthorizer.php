<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 06:33
 */

namespace Roelhem\RbacGraph\Database\Tools;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Models\Authorizable;
use Roelhem\RbacGraph\Contracts\Tools\Authorizer;
use Roelhem\RbacGraph\Contracts\Nodes\Node as NodeContract;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Path;
use Roelhem\RbacGraph\Database\Traits\BelongsToDatabaseGraph;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

class DatabaseAuthorizer implements Authorizer
{

    use BelongsToDatabaseGraph;

    /**
     * @var Authorizable
     */
    protected $authorizable;

    /**
     * @var integer[]
     */
    protected $entryNodeIds;

    /**
     * DatabaseAuthorizer constructor.
     * @param Authorizable $authorizable
     */
    public function __construct($authorizable)
    {
        if(!($authorizable instanceof Authorizable)) {
            throw new \InvalidArgumentException("The Authorizer needs an instance of Authorizable to authorize.");
        }

        $graph = $this->getGraph();
        $entryNodes = $graph->getEntryNodes($authorizable);
        $entryNodeIds = $entryNodes->pluck('id');

        $this->authorizable = $authorizable;
        $this->entryNodeIds = $entryNodeIds;
    }

    /**
     * Returns the authorizable object which is authorized by this authorizer.
     *
     * @return Authorizable
     */
    public function getAutorizable()
    {
        return $this->authorizable;
    }

    /**
     * Returns an array of node ids.
     *
     * @param Collection|array|\Illuminate\Database\Eloquent\Builder $nodes
     * @return Collection|integer[]
     */
    protected function nodeIds($nodes)
    {
        if($nodes instanceof Builder) {
            return $nodes->pluck('id');
        } else {
            return collect($nodes)->map(function($node) {
                return $this->getGraph()->getNodeId($node);
            });
        }
    }

    /**
     * Returns a query-builder with only the paths that start on an entry-node of the authorizable object.
     *
     * @return Builder
     */
    protected function entryNodePathsQuery() {
        return Path::query()->whereIn('first_node_id', $this->entryNodeIds);
    }

    /**
     * Authorizes the provided node and returns the verdict.
     *
     * @param NodeContract|string|integer $node
     * @param array $attributes
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function allows($node, $attributes = [])
    {
        return $this->entryNodePathsQuery()->endsAt($node)->exists();
    }

    /**
     * Returns if there is at least one node in the provided nodes for which the user is authorized.
     *
     * @param Collection|array|\Illuminate\Database\Eloquent\Builder $nodes
     * @param array $attributes
     * @return boolean
     */
    public function any($nodes, $attributes = [])
    {
        $lastNodeIds = $this->nodeIds($nodes);

        $query = $this->entryNodePathsQuery()
            ->whereIn('last_node_id', $lastNodeIds);

        return $query->exists();
    }

    /**
     * Returns if the authorizable in this authorizer is a super-user.
     *
     * @return boolean
     */
    public function isSuper()
    {
        return $this->any(Node::type(NodeType::SUPER_ROLE));
    }
}