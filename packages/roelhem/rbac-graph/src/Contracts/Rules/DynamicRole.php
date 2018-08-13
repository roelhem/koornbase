<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 08:50
 */

namespace Roelhem\RbacGraph\Contracts\Rules;



use Roelhem\RbacGraph\Contracts\Models\Authorizable;

interface DynamicRole extends BaseRule
{

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

    /**
     * An array of all the authorizable-types.
     *
     * @return array
     */
    public function forAuthorizableTypes();

    /**
     * Returns if the dynamic role should be assigned to the provided authorizable object.
     *
     * @param Authorizable $authorizable
     * @return boolean
     */
    public function shouldAssignTo($authorizable);

}