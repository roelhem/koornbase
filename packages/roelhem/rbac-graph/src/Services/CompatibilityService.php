<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 14:42
 */

namespace Roelhem\RbacGraph\Services;


use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Models\Authorizable;
use Roelhem\RbacGraph\Contracts\Graphs\AuthorizableGraph;
use Roelhem\RbacGraph\Database\Tools\DatabaseAuthorizer;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Enums\RuleAttribute;
use Roelhem\RbacGraph\Rules\CallbackBag;

/**
 * Class CompatibilityService
 *
 * A service class that makes the RbacGraph compatible with the native Authorization-system of Laravel.
 *
 * @package Roelhem\RbacGraph\Services
 */
class CompatibilityService
{

    /**
     * @var AuthorizableGraph
     */
    protected $graph;

    /**
     * CompatibilityService constructor.
     *
     * @param AuthorizableGraph $graph
     */
    public function __construct(AuthorizableGraph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * Creates a new authorizer that can be used to handle the native requests.
     *
     * @param Authorizable $authorizable
     * @return DatabaseAuthorizer
     */
    public function createAuthorizer($authorizable) {
        return new DatabaseAuthorizer($authorizable);
    }





    /**
     * Makes a native Laravel Gate compatible with the RbacGraph.
     */
    public function registerGate() {
        \Gate::before(function($user, $ability, $arguments = []) {
            return $this->handleAuthRequest($user, $ability, $arguments);
        });
    }

    /**
     * Handles an authorization-request from a $gate.
     *
     * @param Authorizable $user
     * @param string $ability
     * @param array $arguments
     * @return boolean|null
     * @throws
     */
    public function handleAuthRequest($user, $ability, $arguments = []) {

        $bag = new CallbackBag([
            RuleAttribute::USER => $user,
            RuleAttribute::CALL_ABILITY => $ability,
            RuleAttribute::ARGUMENTS => $arguments
        ]);

        // Create the authorizer
        $authorizer = $this->createAuthorizer($user);


        // Check the super-user
        if($authorizer->isSuper()) {
            return true;
        }


        // Check if the request is for an modelAbility.
        $modelClass = $this->modelClassFromArguments($arguments);
        if($modelClass !== null && $this->nameUsedByModelAbilities($ability)) {
            $bag[RuleAttribute::MODEL_CLASS] = $modelClass;
            $modelAbilities = $this->searchModelAbilities($ability, $modelClass);
            $bag[RuleAttribute::CALL_MATCHES] = $modelAbilities;
            return $authorizer->any($modelAbilities, $bag);
        }

        // Check if the request is for an normal ability.
        if($this->abilityNameExists($ability)) {
            $abilities = $this->searchAbilities($ability);
            $bag[RuleAttribute::CALL_MATCHES] = $abilities;
            return $authorizer->any($abilities, $bag);
        }


        // Check if there is a node with exactly the same name as the ability.
        if($this->graph->hasNodeName($ability)) {
            $node = $this->graph->getNodeByName($ability);
            $bag[RuleAttribute::CALL_MATCHES] = collect([$node]);
            return $authorizer->allows($node, $bag);
        }

        // Return the null value (signals that laravel should make a decision by itself.)
        return null;
    }


    /**
     * Authorizes an ModelAbility node.
     *
     * @param string $ability
     * @param string $modelClass
     * @return Collection|Node[]
     */
    protected function searchModelAbilities($ability, $modelClass) {
        return $this->graph->getNodesWith([
            'options' => [
                'ability' => $ability,
                'modelClass' => $modelClass
            ],
            'type' => NodeType::MODEL_ABILITY
        ]);

    }

    /**
     * Checks if the provided ability-name is used by ModelAbilities.
     *
     * @param string $ability
     * @return boolean
     */
    protected function nameUsedByModelAbilities($ability) {
        return $this->graph->hasNodesWith([
            'options' => [
                'ability' => $ability
            ],
            'type' => NodeType::MODEL_ABILITY
        ]);
    }

    /**
     * Checks if there exists an Ability with the provided name.
     *
     * @param string $ability
     * @return boolean
     */
    protected function abilityNameExists($ability) {
        return $this->graph->hasNodesWith([
            'options' => [
                'ability' => $ability
            ],
            'type' => NodeType::ABILITY
        ]);
    }

    /**
     * Searches for abilities with the provided name.
     *
     * @param string $ability
     * @return Collection|Node[]
     */
    protected function searchAbilities($ability) {
        return $this->graph->getNodesWith([
            'options' => [
                'ability' => $ability,
            ],
            'type' => NodeType::ABILITY
        ]);
    }

    /**
     * Returns the subject modelClass, based on the arguments.
     *
     * @param array $arguments
     * @return null|string
     */
    protected function modelClassFromArguments($arguments = []) {

        if(!is_array($arguments) || count($arguments) === 0) {
            return null;
        }

        $arg = $arguments[0];

        if($arg instanceof Model) {
            return get_class($arg);
        }

        if (is_string($arg) && is_subclass_of($arg, Model::class)) {
            return $arg;
        }

        return null;
    }




}