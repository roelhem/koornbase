<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 29-06-18
 * Time: 22:58
 */

namespace Roelhem\RbacGraph\Rules;

use Illuminate\Foundation\Auth\User;
use Roelhem\RbacGraph\Contracts\Rules\GateRule;
use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;


/**
 * Class StaticRule
 * @package Roelhem\RbacGraph\Rules
 */
class StaticRule extends BaseRule implements GateRule
{

    /**
     * @var bool
     */
    protected $value;

    /**
     * StaticRule constructor.
     *
     * @param boolean $value
     */
    public function __construct($value)
    {
        $this->value = boolval($value);
    }

    /**
     * Returns the static value of this StaticRule.
     *
     * @return bool
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * An array of attributes needed by the constructor to initiate the right rule.
     *
     * @return array|null
     */
    public function constructorArguments()
    {
        return [$this->value];
    }

    /**
     * Returns true if the gate can be traversed, returns false otherwise.
     *
     * @param RuleAttributeBag $attributeBag
     * @return boolean
     */
    public function allows($attributeBag)
    {
        return $this->value;
    }
}