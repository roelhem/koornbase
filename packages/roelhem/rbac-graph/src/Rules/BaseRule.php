<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 08:57
 */

namespace Roelhem\RbacGraph\Rules;

use Roelhem\RbacGraph\Contracts\Rules\BaseRule as BaseRuleContract;
use Roelhem\RbacGraph\Enums\NodeType;

abstract class BaseRule implements BaseRuleContract
{

    /**
     * The class name of the rule or a callable string to a function that initiates this rule.
     *
     * @return string
     */
    public function constructor()
    {
        return get_class($this);
    }

    /**
     * An array of attributes needed by the constructor to initiate the right rule.
     *
     * @return array|null
     */
    public function constructorArguments()
    {
        return null;
    }


}