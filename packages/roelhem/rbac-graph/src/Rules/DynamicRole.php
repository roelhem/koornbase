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
     * @var array
     */
    protected $forAuthorizableTypes = [];

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

    /**
     * An array of all the authorizable-types.
     *
     * @return array
     */
    public function forAuthorizableTypes()
    {
        return $this->forAuthorizableTypes;
    }

}