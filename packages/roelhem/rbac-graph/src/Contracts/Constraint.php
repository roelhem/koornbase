<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 17:09
 */

namespace Roelhem\RbacGraph\Contracts;


interface Constraint
{

    public function run( $parameters );

}