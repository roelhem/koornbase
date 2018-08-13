<?php

namespace Roelhem\RbacGraph\Database;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Models\Authorizable;
use Roelhem\RbacGraph\Contracts\Models\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Contracts\Graphs\AuthorizableGraph;
use Roelhem\RbacGraph\Contracts\Graphs\MutableGraph;
use Roelhem\RbacGraph\Contracts\Rules\DynamicRole;
use Roelhem\RbacGraph\Contracts\Services\RuleSerializer;
use Roelhem\RbacGraph\Graphs\Traits\GraphDefaultEquals;
use Roelhem\RbacGraph\Database\Traits\Graph\GraphAssignmentsImplementation;
use Roelhem\RbacGraph\Database\Traits\Graph\GraphContractImplementation;
use Roelhem\RbacGraph\Database\Traits\Graph\MutableGraphContractImplementation;
use Roelhem\RbacGraph\Enums\NodeType;


/**
 * Class DatabaseGraph
 *
 * The graph object that handles the graph in the database.
 *
 * @package Roelhem\RbacGraph\Database
 */
class DatabaseGraph implements MutableGraph, AuthorizableGraph
{

    use GraphContractImplementation;
    use MutableGraphContractImplementation;
    use GraphAssignmentsImplementation;
    use GraphDefaultEquals;

    /**
     * @var RuleSerializer
     */
    protected $ruleSerializer;

    /**
     * DatabaseGraph constructor.
     *
     * @param RuleSerializer $ruleSerializer
     */
    public function __construct(RuleSerializer $ruleSerializer)
    {
        $this->ruleSerializer = $ruleSerializer;
    }

    /**
     * Returns the nodes that are authorized for the given authorizable object in the initial state.
     *
     * (These are the the nodes that are granted to the authorizable before walking trough the graph.)
     *
     * @param Authorizable $authorizable
     * @return Collection|Node[]
     */
    public function getEntryNodes($authorizable)
    {
        $res = [];

        foreach ($authorizable->getAuthorizableGroups() as $group) {
            $res[] = $this->getEntryNodes($group);
        }

        if($authorizable instanceof RbacDatabaseAssignable) {
            $res[] = $this->getAssignedNodes($authorizable);
        }

        foreach($this->getPotentialDynamicRoles($authorizable->getType()) as $node) {
            if($node instanceof Node) {
                $rule = $this->ruleSerializer->rule($node->getOption('rule'));

                if($rule instanceof DynamicRole) {
                    if($rule->shouldAssignTo($authorizable)) {
                        $res[] = $node;
                    }
                }
            }
        }


        return collect($res)->flatten();
    }

    public function getPotentialDynamicRoles($type)
    {
        return Node::type(NodeType::DYNAMIC_ROLE)
            ->whereJsonContains('options',['for' => [$type]])
            ->get();
    }

    /**
     * Returns a query that searches nodes with the provided parameters in this graph.
     *
     * @param array $params = []
     * @return Builder
     */
    protected function nodesWith($params = []) {

        $query = Node::query();


        $options = array_get($params, 'options');
        if(is_array($options) && count($options) > 0) {
            $query->whereJsonContains('options', $options);
        }


        $type = array_get($params, 'type');
        if($type !== null) {
            if(is_array($type) || $type instanceof Arrayable) {
                $query->whereIn('type', collect($type)->map(function($type) {
                    return NodeType::by($type)->getValue();
                })->values()->all());
            } else {
                $query->where('type','=',NodeType::by($type)->getValue());
            }
        }

        return $query;
    }

    /**
     * Returns if there exists a node in the graph with the provided parameters.
     *
     * @param array $params
     * @return boolean
     */
    public function hasNodesWith($params = []) {
        return $this->nodesWith($params)->exists();
    }

    /**
     * Returns the nodes in the graph that have the provided parameters.
     *
     * @param array $params
     * @return Collection|Node[]
     */
    public function getNodesWith($params = []) {
        return $this->nodesWith($params)->get();
    }

}