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
use Roelhem\RbacGraph\Contracts\Rules\GateRule;
use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag;
use Roelhem\RbacGraph\Contracts\Tools\Authorizer;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Path;
use Roelhem\RbacGraph\Database\Traits\BelongsToDatabaseGraph;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Enums\RuleAttribute;
use Roelhem\RbacGraph\Rules\CallbackBag;

class DatabaseAuthorizer implements Authorizer
{

    use BelongsToDatabaseGraph;

    /**
     * @var Authorizable
     */
    protected $authorizable;

    /**
     * @var Collection|Node[]
     */
    protected $entryNodes;


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

        $this->authorizable = $authorizable;
        $this->entryNodes = $entryNodes;
    }

    /**
     * @inheritdoc
     */
    public function getAutorizable()
    {
        return $this->authorizable;
    }

    /**
     * Creates a new RuleAttributeBag for this authorizer, based on the provided attribute bag.
     *
     * @param RuleAttributeBag|array|null $bag
     * @return CallbackBag
     */
    public function createBag($bag = null) {
        if($bag instanceof CallbackBag) {
            $bag = clone $bag;
        } elseif($bag instanceof RuleAttributeBag) {
            $bag = new CallbackBag($bag->getAll());
        } elseif(is_array($bag)) {
            $bag = new CallbackBag($bag);
        } else {
            $bag = new CallbackBag();
        }

        $bag->authorizer = $this;
        $bag->graph = $this->getGraph();
        $bag->authorizable = $this->getAutorizable();
        $bag->entry_nodes = $this->entryNodes;

        return $bag;
    }


    /**
     * Returns a query-builder with only the paths that start on an entry-node of the authorizable object.
     *
     * @return Builder
     */
    protected function entryNodePathsQuery() {
        return Path::query()
            ->whereIn('first_node_id', $this->entryNodes->map(function(Node $node) {
                return $node->id;
            }))
            ->orderBy('rules_count');
    }

    /**
     * @inheritdoc
     */
    public function allows($node, $bag = null)
    {
        $bag = $this->createBag($bag);
        $node = $this->getGraph()->getNode($node);
        $bag[RuleAttribute::AUTHORIZED_NODE] = $node;
        $paths = $this->entryNodePathsQuery()->endsAt($node)->get();
        return $this->allowsAnyPath($paths, $bag);
    }

    /**
     * @inheritdoc
     */
    public function any($nodes, $bag = null)
    {
        if($nodes instanceof Builder) {
            $nodes = $nodes->get();
        } else {
            $nodes = collect($nodes)->map(function($node) {
                return $this->getGraph()->getNode($node);
            });
        }
        $paths = $this->entryNodePathsQuery()
            ->whereIn('last_node_id', $nodes->map(function(Node $node) {
                return $node->id;
            }))
            ->get();
        return $this->allowsAnyPath($paths, $this->createBag($bag));
    }

    /**
     * @param Path $path
     * @param RuleAttributeBag $bag
     * @return bool
     */
    protected function allowPath($path, $bag) {
        $rules = $path->rules;

        $bag = clone $bag;
        $bag->path = $path;
        $bag->path_rules = $rules;

        foreach($rules as $rule) {
            if(!($rule instanceof GateRule) || !$rule->allows($bag)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array|Path[] $paths
     * @param RuleAttributeBag $bag
     * @return bool
     */
    protected function allowsAnyPath($paths, $bag) {

        $bag[RuleAttribute::POSSIBLE_PATHS] = $paths;

        foreach ($paths as $path) {
            if($this->allowPath($path, $bag)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isSuper()
    {
        return $this->any(Node::type(NodeType::SUPER_ROLE));
    }
}