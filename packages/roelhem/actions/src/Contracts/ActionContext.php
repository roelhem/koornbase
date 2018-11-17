<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 15:03
 */

namespace Roelhem\Actions\Contracts;
use Laravel\Socialite\Contracts\User;


/**
 * Interface ActionContextContract
 *
 * Describes the context in which the action is executed.
 *
 * @package Roelhem\Actions\Contracts
 */
interface ActionContext
{
    /**
     * Checks if this action context has the provided ability.
     *
     * @param string $ability
     * @param array $attributes
     * @return boolean
     */
    public function can($ability, $attributes = []);

    /**
     * Returns the user of this context.
     *
     * @return User|null
     */
    public function user();
}