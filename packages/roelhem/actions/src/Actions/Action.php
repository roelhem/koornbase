<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 16:02
 */

namespace Roelhem\Actions\Actions;


use Illuminate\Auth\Access\AuthorizationException;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\Actions\Contracts\ActionContract;
use Roelhem\Actions\Contracts\ActionGraphContext;
use Roelhem\RbacGraph\Contracts\Graphs\AuthorizableGraph;
use Roelhem\RbacGraph\Contracts\Models\Authorizable;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Database\Tools\DatabaseAuthorizer;
use Roelhem\RbacGraph\Enums\NodeType;

abstract class Action implements ActionContract
{

    /** @var string|null */
    protected $name;

    /** @var string|null */
    protected $description;

    /**
     * Runs the action with the provided arguments in the provided context. It returns the result of the action if
     * the action was successful. Otherwise, it will throw an exception.
     *
     * @param array $args
     * @param null|ActionContext $context
     * @return mixed
     * @throws
     */
    public function run($args = [], ?ActionContext $context = null)
    {
        // Authorize the global access to this action.
        $this->authorizeAccess($context);

        // Validate the arguments
        $validator = $this->getValidator($args);
        $validArgs = $validator->validate();

        // Some extra authorization steps that make use of the validated arguments.
        $this->authorizeArguments($validArgs, $context);


        // Handle the action.
        return $this->handle($validArgs, $context);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ACTION META-INFO ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function name()
    {
        $name = $this->name;
        if($name === null) {
            $reflection = new \ReflectionClass($this);
            $name = $reflection->getShortName();
            if(ends_with($name,'Action')) {
                $name = camel_case(str_before($name, 'Action'));
            }
        }
        return $name;
    }

    /** @inheritdoc */
    public function description()
    {
        return $this->description;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- VALIDATION ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns an array that defines the rules for the validator to validate the input arguments of the action.
     *
     * @return array
     */
    public function rules() {
        $args = collect($this->args());
        return $args->flatMap(function($value, $key) {
            $rules = array_get($value, 'rules', []);
            $name = array_get($value, 'alias', $key);
            return [$name => $rules];
        })->toArray();
    }


    /**
     * Returns the validator object that is used to validate the arguments of this action.
     *
     * @param array $args
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator($args = []) {
        return \Validator::make($args, $this->rules())->after([$this, 'afterValidation']);
    }

    /**
     * Some extra validation, after the standard validation from the rules.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function afterValidation($validator) {

    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- AUTHORIZATION -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @var Node|false|null
     */
    protected $permissionNode;

    /**
     * Returns the PermissionNode from the RbacGraph that determines the global access to this action.
     *
     * @return Node|null
     */
    public function getPermissionNode() {
        if($this->permissionNode === null) {
            /** @var AuthorizableGraph $graph */
            $graph = resolve(AuthorizableGraph::class);

            $nodes = $graph->getNodesWith([
                'type' => NodeType::ACTION_PERMISSION,
                'options' => [
                    'actionName' => $this->name(),
                ]
            ]);

            if($nodes->count() >= 1) {
                $this->permissionNode = $nodes->get(0);
            } else {
                $this->permissionNode = false;
            }
        }

        if($this->permissionNode === false) {
            return null;
        }

        return $this->permissionNode;
    }

    /**
     * Returns whether or not this permission has a PermissionNode.
     *
     * @return bool
     */
    public function hasPermissionNode() {
        $permissionNode = $this->getPermissionNode();
        return $permissionNode instanceof Node;
    }

    /**
     * Checks if the current context is allowed to run this action.
     *
     * @param null|ActionContext $context
     * @throws AuthorizationException
     */
    public function authorizeAccess(?ActionContext $context = null)
    {
        if($this->hasPermissionNode()) {
            $authorizer = $this->getAuthorizer($context);

            if($authorizer === null) {
                throw new AuthorizationException('Unable to authorize the access to this action, because the context doesn\'t support it.');
            }

            if($authorizer->isSuper()) {
                return;
            }

            if(!$authorizer->allows($this->getPermissionNode())) {
                throw new AuthorizationException('Not allowed to access the action `'.$this->name().'`.');
            };
        }
    }

    /**
     * Some authorization steps that make use of the input arguments of this action.
     *
     * @param array $validArgs
     * @param null|ActionContext $context
     * @throws
     */
    public function authorizeArguments($validArgs = [], ?ActionContext $context = null) {

    }

    /**
     * Returns the authorizer that can be used to authorize nodes for this action.
     *
     * @param null|ActionContext $context
     * @return null|\Roelhem\RbacGraph\Contracts\Tools\Authorizer
     */
    protected function getAuthorizer(?ActionContext $context = null)
    {
        if($context === null) {
            return null;
        }

        if($context instanceof ActionGraphContext) {
            return $context->getAuthorizer();
        }

        $user = $context->user();
        if($user instanceof Authorizable) {
            return new DatabaseAuthorizer($user);
        }

        return null;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- EXECUTION ------------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Handles the action with all the validated arguments.
     *
     * @param array $validArgs
     * @param null|ActionContext $context
     * @return mixed
     */
    abstract protected function handle($validArgs = [], ?ActionContext $context = null);
}