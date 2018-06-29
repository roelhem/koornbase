<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 29-06-18
 * Time: 23:42
 */

namespace Roelhem\RbacGraph\Contracts\Services;


use Roelhem\RbacGraph\Contracts\Rules\BaseRule;

interface RuleSerializer
{

    /**
     * Converts an option to a rule.
     *
     * @param BaseRule|array $rule
     * @return array
     */
    public function option($rule);

    /**
     * Converts an rule-option value to a rule instance.
     *
     * @param BaseRule|array $rule
     * @return BaseRule
     */
    public function rule($rule);

}