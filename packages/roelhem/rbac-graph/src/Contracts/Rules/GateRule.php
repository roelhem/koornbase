<?php

namespace Roelhem\RbacGraph\Contracts\Rules;

use Roelhem\RbacGraph\Contracts\Models\Authorizable;
use Roelhem\RbacGraph\Contracts\Nodes\Node;

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
     * @param RuleAttributeBag $attributeBag
     * @return boolean
     */
    public function allows($attributeBag);

}