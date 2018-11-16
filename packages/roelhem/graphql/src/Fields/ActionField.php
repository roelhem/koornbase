<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 14:39
 */

namespace Roelhem\GraphQL\Fields;


use Roelhem\Actions\Contracts\ActionContract;
use Roelhem\GraphQL\Resolvers\ActionResolver;

class ActionField extends MutationField
{

    /** @var mixed|ActionContract|string */
    protected $action;

    /**
     * ActionField constructor.
     * @param string|ActionContract $action
     * @param array $config
     */
    public function __construct($action, array $config = [])
    {
        // Initializing the action factory.
        if(is_string($action)) {
            $action = app()->make($action);
        }

        if(!($action instanceof ActionContract)) {
            throw new \InvalidArgumentException("Invalid argument for parameter 'action'. Can't create the Action instance.");
        }
        $this->action = $action;

        // recursively call the mutation constructor.
        parent::__construct($config);
    }


    /** @inheritdoc */
    public function resolver()
    {
        return new ActionResolver($this->action);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- FIELD META-INFO ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function name()
    {
        $name = parent::name();
        if(empty($name)) {
            $name = camel_case($this->action->getName());
        }
        return $name;
    }

    /** @inheritdoc */
    public function description()
    {
        $description = parent::description();
        if(empty($description)) {
            $description = $this->action->getDescription();
        }
        return $description;
    }

}