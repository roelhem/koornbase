<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 05:09
 */

namespace Roelhem\RbacGraph\Contracts\Rules;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Graphs\AuthorizableGraph;
use Roelhem\RbacGraph\Contracts\Graphs\Path;
use Roelhem\RbacGraph\Contracts\Models\Authorizable;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Contracts\Tools\Authorizer;
use Roelhem\RbacGraph\Enums\RuleAttribute;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Interface RuleAttributeBag
 *
 *
 * @package Roelhem\RbacGraph\Contracts\Rules
 *
 * @property Authorizer $authorizer              The authorizer that is performing the authorization.
 * @property AuthorizableGraph $graph            The graph on which the authorization is performed.
 * @property Authorizable $authorizable          The authorizable object of this authorization.
 * @property Collection|Node[] $entry_nodes      The entry nodes available for the current authorizable object.
 *
 * @property Authenticatable $user               The authenticatable object on which needs to be authorized.
 * @property string $call_ability                The ability string, passed to the authorize-call.
 * @property Collection|Node[] $call_matches     The nodes that matches the authorization call.
 * @property array $arguments                    The attributes array, passed to the authorize-call.
 *
 * @property string $model_class
 * @property Model $model
 *
 * @property Node $rule_node                     The node on which the rule was defined.
 * @property Path $path                          The path that is authorized (and contains the node with a rule).
 * @property Node $entry_node                    The first node of the path that is authorized.
 * @property Node $authorized_node               The node that was requested by the authorized request.
 *
 * @property BaseRule[] $path_rules              A list of all the rules on the path that need to be checked.
 * @property Collection|Path[] $possible_paths   All parallel paths of the authorization, including it's own path.
 *
 */
interface RuleAttributeBag extends \ArrayAccess
{

    /**
     * Returns the attribute with the provided attributeName.
     *
     * @param RuleAttribute|string $attribute
     * @return mixed
     */
    public function get($attribute);

    /**
     * Returns all the explicitly set attributes.
     *
     * @return array
     */
    public function getAll();

    /**
     * Returns if the RuleAttributeBag has an value for the provided attribute.
     *
     * @param RuleAttribute|string $attribute
     * @return boolean
     */
    public function has($attribute);

    /**
     * Returns if the RuleAttributeBag has an explicit value of the provided attribute.
     *
     * @param $attribute
     * @return boolean
     */
    public function hasExplicit($attribute);

    /**
     * Sets the value of an attribute.
     *
     * @param RuleAttribute|string $attribute
     * @param mixed $value
     * @return void
     */
    public function set($attribute, $value);

    /**
     * resets the value of an attribute.
     *
     * @param RuleAttribute|string $attribute
     * @return void
     */
    public function unset($attribute);

}