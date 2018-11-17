<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 15:01
 */

namespace Roelhem\Actions\Contracts;


/**
 * Interface ActionContract
 *
 * @package Roelhem\Actions\Contracts
 */
interface ActionContract
{

    /**
     * Runs the action with the provided arguments in the provided context. It returns the result of the action if
     * the action was successful. Otherwise, it will throw an exception.
     *
     * @param array $args
     * @param null|ActionContext $context
     * @return mixed
     * @throws
     */
    public function run($args = [], ?ActionContext $context = null);

    /**
     * Method that returns the name of the action.
     *
     * @return string
     */
    public function name();

    /**
     * Method that returns the description of the action. If there is no description available, this method will return
     * null.
     *
     * @return string|null
     */
    public function description();

    /**
     * Method that returns the definition of the available arguments of this action.
     *
     * @return array
     */
    public function args();

}