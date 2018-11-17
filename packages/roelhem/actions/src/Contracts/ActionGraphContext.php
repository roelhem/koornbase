<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 23:29
 */

namespace Roelhem\Actions\Contracts;


use Roelhem\RbacGraph\Contracts\Tools\Authorizer;

interface ActionGraphContext extends ActionContext
{

    /**
     * @return Authorizer
     */
    public function getAuthorizer();

}