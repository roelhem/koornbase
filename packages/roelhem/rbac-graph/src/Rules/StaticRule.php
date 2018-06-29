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


/**
 * Class StaticRule
 * @package Roelhem\RbacGraph\Rules
 */
class StaticRule implements GateRule
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
     * The class name of the rule or a callable string to a function that initiates this rule.
     *
     * @return string
     */
    public function constructor()
    {
        return static::class;
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
     * @param User $user
     * @param string $node
     * @param array $attributes
     * @return boolean
     */
    public function allows($user, $node, $attributes = [])
    {
        return $this->value;
    }
}