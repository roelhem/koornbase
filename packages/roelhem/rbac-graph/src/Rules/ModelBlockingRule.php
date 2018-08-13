<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 04:03
 */

namespace Roelhem\RbacGraph\Rules;


use Roelhem\RbacGraph\Contracts\Models\Authorizable;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Contracts\Rules\ModelRule;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class ModelBlockingRule extends BaseRule implements ModelRule
{

    protected $for = [];

    public function __construct($classNames = [])
    {
        $this->for = $classNames;
    }

    /**
     * An array of attributes needed by the constructor to initiate the right rule.
     *
     * @return array|null
     */
    public function constructorArguments()
    {
        return [$this->for];
    }

    /**
     * Returns true if the gate can be traversed, returns false otherwise.
     *
     * @param AttributeBag $attributeBag
     * @return boolean
     */
    public function allows($attributeBag)
    {
        return false;
    }

    /**
     * @return array
     */
    public function for()
    {
        return $this->for;
    }
}