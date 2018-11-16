<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-10-18
 * Time: 06:44
 */

namespace App\Contracts;

/**
 * Interface ActionContract
 *
 * @package App\Contracts
 */
interface ActionContract
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ACTION META-INFO ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the name of this action.
     *
     * @return string
     */
    public function name();

    /**
     * Returns a description of this action.
     *
     * @return string
     */
    public function description();

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- AUTHORIZATION -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the context of this action.
     *
     * @return ActionContextContract
     */
    public function getContext();

    /**
     * Authorizes this action
     *
     * @return void
     */
    public function authorize();

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- VALIDATION ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns an array of all the validation rules of this action.
     *
     * @return array
     */
    public function rules();

    /**
     * Returns a validator that will be used to validate the arguments of this action.
     *
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator();

    /**
     * Validates this action
     *
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate();

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- EXECUTION ------------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Executes this action.
     *
     * @return mixed
     */
    public function execute();

}