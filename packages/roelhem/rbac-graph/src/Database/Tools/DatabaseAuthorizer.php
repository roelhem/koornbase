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
use Roelhem\RbacGraph\Contracts\Rules\QueryRule;
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
     * Adds some filters to a query such that only the models with permission are shown.
     *
     * @param Builder $query
     * @param Node|string|integer $node
     * @param RuleAttributeBag $bag
     * @return Builder
     */
    public function queryFilter($query, $node, $bag = null)
    {
        $bag = $this->createBag($bag);
        $paths = $this->entryNodePathsQuery()->endsAt($node)->get();
        $bag[RuleAttribute::POSSIBLE_PATHS] = $paths;

        $subQueries = [];
        foreach ($paths as $path) {
            if($path instanceof Path) {
                // If there is a path that has no rule, all models are permitted, so no query should be send
                if($path->rules_count === 0) {
                    return $query;
                }

                // If there is a path with only query rules, a new subquery can be created.
                if($this->checkQueryRules($path)) {
                    $subQueries[] = function($query) use ($path, $bag) {
                        return $this->pathQueryFilter($query, $path, $bag);
                    };
                }
            }
        }

        // Stitching the queries together.
        if(count($subQueries) > 0) {
            return $query->where(function($query) use ($subQueries) {
                foreach ($subQueries as $subQueryCallable) {
                    $query->orWhere($subQueryCallable);
                }
                return $query;
            });
        }

        // Block all entries if no valid rule was found.
        return $query->whereRaw('FALSE');
    }

    /**
     * Returns if the provided path has query-rules only.
     *
     * @param Path $path
     * @return boolean
     */
    protected function checkQueryRules($path)
    {
        foreach ($path->rules as $rule) {
            if(!($rule instanceof QueryRule)) {
                return false;
            }
        }
        return true;
    }


    /**
     * Applies the query filters of one path.
     *
     * @param Builder $query
     * @param Path $path
     * @param RuleAttributeBag $bag
     * @return Builder
     */
    protected function pathQueryFilter($query, $path, $bag)
    {
        $bag = clone $bag;
        $bag[RuleAttribute::PATH] = $path;
        $rules = $path->rules;
        $bag[RuleAttribute::PATH_RULES] = $rules;
        foreach ($rules as $rule) {
            if($rule instanceof QueryRule) {
                $query = $rule->queryFilter($query, $bag);
            } else {
                return $query->whereRaw('FALSE');
            }
        }
        return $query;
    }

    /**
     * Returns if the authorizable object and the provided node are connected.
     *
     * That is, there exists a path from the authorizable object to the provided node, but there may be some
     * gates with rules that deny the access to that node.
     *
     * @param $node
     * @return boolean
     */
    public function connected($node)
    {
        return $this->entryNodePathsQuery()->endsAt($node)->exists();
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