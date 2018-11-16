<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-10-18
 * Time: 06:49
 */

namespace App\Actions;


use App\Contracts\ActionContextContract;
use App\Contracts\ActionContract;

abstract class AbstractAction implements ActionContract
{
    /** @var string|null */
    protected $name;

    /** @var string|null */
    protected $description;

    /** @var ActionContextContract */
    protected $context;

    /** @var array */
    protected $args;

    /** @var array */
    protected $validArgs;


    protected $validator;

    /**
     * AbstractAction constructor.
     * @param array $args
     * @param ActionContextContract|null $context
     */
    public function __construct(array $args = [], ?ActionContextContract $context = null)
    {
        $this->context = $context;
        $this->args = $args;

        $this->validator = \Validator::make($args,$this->rules());
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
                $name = str_before($name, 'Action');
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
    // ----- AUTHORIZATION -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function getContext()
    {
        return $this->context;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- VALIDATION ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /** @inheritdoc */
    public function validate()
    {
        $this->validArgs = $this->validator->validate();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- EXECUTION ------------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    public function execute()
    {
        $this->authorize();
        $this->validate();
        return $this->handle($this->validator->getData());
    }

    /**
     * Method that handles the execution of the action after authorization and validation.
     *
     * @param array $args
     * @return mixed
     */
    abstract public function handle($args);

}