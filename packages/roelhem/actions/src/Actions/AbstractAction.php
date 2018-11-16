<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 16:02
 */

namespace Roelhem\Actions\Actions;


use Roelhem\Actions\Contracts\ActionContextContract;
use Roelhem\Actions\Contracts\ActionContract;

abstract class AbstractAction implements ActionContract
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
     * @param null|ActionContextContract $context
     * @return mixed
     * @throws
     */
    public function run($args = [], ?ActionContextContract $context = null)
    {
        // Validate the arguments
        $validator = $this->getValidator();
        $validArgs = $validator->validate();

        // Handle the action.
        return $this->handle($validArgs);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ACTION META-INFO ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function getName()
    {
        $name = $this->name;
        if($name === null) {
            $reflection = new \ReflectionClass($this);
            $name = $reflection->getShortName();
            if(ends_with($name,'Action')) {
                $name = str_before($name, 'Action');
            }
        }
        return $name;
    }

    /** @inheritdoc */
    public function getDescription()
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
    abstract public function rules();


    /**
     * Returns the validator object that is used to validate the arguments of this action.
     *
     * @param array $args
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator($args = []) {
        return \Validator::make($args, $this->rules());
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- EXECUTION ------------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Handles the action with all the validated arguments.
     *
     * @param array $validArgs
     * @param null|ActionContextContract $context
     * @return mixed
     */
    abstract protected function handle($validArgs = [], ?ActionContextContract $context = null);
}