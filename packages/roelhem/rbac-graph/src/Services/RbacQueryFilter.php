<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 15:23
 */

namespace Roelhem\RbacGraph\Services;


use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Tools\DatabaseAuthorizer;
use Roelhem\RbacGraph\Database\Traits\BelongsToDatabaseGraph;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Enums\RuleAttribute;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Rules\CallbackBag;

class RbacQueryFilter
{

    use BelongsToDatabaseGraph;

    /**
     * @var Model|null
     */
    protected $model;

    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @var string
     */
    protected $ability;

    /**
     * @var Node
     */
    protected $node;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * RbacQueryFilter constructor.
     * @param Model|string $model
     * @param string $ability
     * @param User|null $user
     */
    public function __construct($model, $ability = 'view', $user = null)
    {
        if(is_string($model)) {
            $this->modelClass = $model;
        } elseif($model instanceof Model) {
            $this->model = $model;
            $this->modelClass = get_class($model);
        }


        $this->ability = $ability;

        $this->node = $this->findNodes()->first();


        if($user === null) {
            $user = \Auth::user();
        }
        $this->user = $user;
    }


    /**
     * @return Collection|Node[]
     */
    protected function findNodes() {
        return $this->getGraph()->getNodesWith([
            'type' => NodeType::MODEL_ABILITY,
            'options' => [
                'ability' => $this->ability,
                'modelClass' => $this->modelClass
            ]
        ]);
    }

    /**
     * @return DatabaseAuthorizer
     */
    protected function getAuthorizer()
    {
        return new DatabaseAuthorizer($this->user);
    }

    /**
     * @return RuleAttributeBag
     */
    protected function getRuleAttributeBag()
    {
        $res = new CallbackBag([
            RuleAttribute::AUTHORIZED_NODE => $this->node,
            RuleAttribute::MODEL_CLASS => $this->modelClass,
            RuleAttribute::CALL_ABILITY => $this->ability,
            RuleAttribute::CALL_MATCHES => function() {
                return $this->findNodes();
            },
            RuleAttribute::USER => $this->user,
        ]);

        if($this->model instanceof Model) {
            $res[RuleAttribute::MODEL] = $this->model;
        }

        return $res;
    }


    /**
     * @param Builder $query
     * @return Builder
     */
    public function filter($query) {
        $authorizer = $this->getAuthorizer();

        if($authorizer->isSuper()) {
            return $query;
        }

        if(!($this->node instanceof Node)) {
            return $query->whereRaw('FALSE');
        }

        $bag = $this->getRuleAttributeBag();
        return $authorizer->queryFilter($query, $this->node, $bag);
    }

    /**
     * @return \Closure
     */
    public static function eagerLoadingConstraintClosure() {


        return function ($query) {
            if ($query instanceof Relation) {
                try {
                    return (new static($query->getRelated()))->filter($query);
                } catch (NodeNotFoundException $exception) {}
            }
            return $query;
        };


    }

}