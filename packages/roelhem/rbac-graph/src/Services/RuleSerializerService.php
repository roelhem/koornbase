<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 29-06-18
 * Time: 23:45
 */

namespace Roelhem\RbacGraph\Services;


use Roelhem\RbacGraph\Contracts\Rules\BaseRule;
use Roelhem\RbacGraph\Contracts\Services\RuleSerializer;
use Roelhem\RbacGraph\Rules\StaticRule;

class RuleSerializerService implements RuleSerializer
{

    const OPTION_CONSTRUCTOR = 'constructor';
    const OPTION_ARGUMENTS = 'arguments';
    const OPTION_DEFAULT = 'default';

    /**
     * Converts a rule to the option value.
     *
     * @param BaseRule|array $rule
     * @return array
     */
    public function option($rule)
    {
        if(is_array($rule)) {
            $rule = $this->rule($rule);
        }

        $res = [];

        // Constructor
        $constructor = $rule->constructor();
        if(!is_string($constructor) || !is_callable($constructor)) {
            $constructor = get_class($rule);
        }
        $res[self::OPTION_CONSTRUCTOR] = $constructor;


        // Attributes
        $arguments = $rule->constructorArguments();
        if (is_array($arguments) && count($arguments)) {
            $res[self::OPTION_ARGUMENTS] = $arguments;
        }

        // Return the result
        return $res;
    }

    /**
     * Converts a rule-option value to a rule instance.
     *
     * @param BaseRule|array $rule
     * @return BaseRule
     */
    public function rule($rule)
    {
        if($rule instanceof BaseRule) {
            return $rule;
        }

        if(!is_array($rule)) {
            throw new \InvalidArgumentException("The rule must be an instance of BaseRule or an array.");
        }

        $constructor = array_get($rule, self::OPTION_CONSTRUCTOR);
        $arguments = array_get($rule, self::OPTION_ARGUMENTS, []);

        if(class_exists($constructor)) {
            return new $constructor(...$arguments);
        } elseif(is_callable($constructor)) {
            return $constructor(...$arguments);
        } else {
            return new StaticRule(array_get($rule, self::OPTION_DEFAULT, false));
        }
    }
}