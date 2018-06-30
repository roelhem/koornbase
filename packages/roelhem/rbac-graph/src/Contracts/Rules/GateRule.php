<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 29-06-18
 * Time: 22:38
 */

namespace Roelhem\RbacGraph\Contracts\Rules;

use Roelhem\RbacGraph\Contracts\Models\Authorizable;

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
     * @param Authorizable $authorizable
     * @param string $node
     * @param array $attributes
     * @return boolean
     */
    public function allows($authorizable, $node, $attributes = []);

}