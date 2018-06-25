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
     * @return string
     */
    public function constructor();

    /**
     * An array of attributes needed by the constructor to initiate the right rule.
     *
     * @return array|null
     */
    public function constructorAttributes();

    /**
     * Returns the NodeType of the node on which this rule can work.
     *
     * @return NodeType
     */
    public function nodeType();

    /**
     * Returns a default name for a node that contains this rule.
     *
     * @return string
     */
    public function defaultNodeName();


    /**
     * Returns a default title for a node that contains this rule.
     *
     * @return string|null
     */
    public function defaultNodeTitle();

    /**
     * Returns a default description for a node that contains this rule.
     *
     * @return string|null
     */
    public function defaultNodeDescription();

}