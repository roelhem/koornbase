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
     * Stores the nodeType.
     *
     * @var int|NodeType
     */
    protected $nodeType = NodeType::DEFAULT_NODE;

    /**
     * The default name of a node that uses this rule.
     *
     * @var string
     */
    protected $defaultNodeName;

    /**
     * The default title of a node that uses this rule.
     *
     * @var string|null
     */
    protected $defaultNodeTitle;

    /**
     * The default description of a node that uses this rule.
     *
     * @var string|null
     */
    protected $defaultNodeDescription;


    /**
     * Returns the NodeType of the node on which this rule can work.
     *
     * @return NodeType
     */
    public function nodeType()
    {
        return NodeType::by($this->nodeType);
    }

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
    public function constructorAttributes()
    {
        return null;
    }

    /**
     * Returns a default name for a node that contains this rule.
     *
     * @return string
     */
    public function defaultNodeName()
    {
        return $this->defaultNodeName;
    }

    /**
     * Returns a default title for a node that contains this rule.
     *
     * @return string|null
     */
    public function defaultNodeTitle()
    {
        return $this->defaultNodeTitle;
    }

    /**
     * Returns a default description for a node that contains this rule.
     *
     * @return string|null
     */
    public function defaultNodeDescription()
    {
        return $this->defaultNodeDescription;
    }
}