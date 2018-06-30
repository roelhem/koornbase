<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 03:56
 */

namespace Roelhem\RbacGraph\Contracts\Rules;


interface ModelRule extends GateRule
{

    /**
     * @return array
     */
    public function for();

}