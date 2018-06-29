<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 29-06-18
 * Time: 22:38
 */

namespace Roelhem\RbacGraph\Contracts\Rules;

use Illuminate\Foundation\Auth\User;

/**
 * Contract GateRule
 *
 * A rule that decides if a gate node is traversable.
 *
 * @package Roelhem\RbacGraph\Contracts\Rules
 */
interface GateRule extends BaseRule
{

    /**
     * Returns true if the gate can be traversed, returns false otherwise.
     *
     * @param User $user
     * @param string $node
     * @param array $attributes
     * @return boolean
     */
    public function allows($user, $node, $attributes = []);

}