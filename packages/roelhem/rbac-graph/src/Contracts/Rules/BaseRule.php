<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 08:49
 */

namespace Roelhem\RbacGraph\Contracts\Rules;


use Roelhem\RbacGraph\Enums\NodeType;

interface BaseRule
{

    /**
     * The class name of the rule or a callable string to a function that initiates this rule.
     *
     * @return string|null
     */
    public function constructor();

    /**
     * An array of attributes needed by the constructor to initiate the right rule.
     *
     * @return array|null
     */
    public function constructorArguments();

}