<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 09:02
 */

namespace Roelhem\RbacGraph\Rules;

use Roelhem\RbacGraph\Contracts\Rules\DynamicRole as DynamicRoleContract;
use Roelhem\RbacGraph\Enums\NodeType;

abstract class DynamicRole extends BaseRule implements DynamicRoleContract
{

    protected $nodeType = NodeType::DYNAMIC_ROLE;

    protected $forAuthorizableTypes = [];

    public function nodeType()
    {
        return NodeType::DYNAMIC_ROLE();
    }

    public function forAuthorizableTypes()
    {
        return $this->forAuthorizableTypes;
    }

}