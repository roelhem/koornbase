<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-07-18
 * Time: 05:58
 */

namespace App\Actions;


abstract class AbstractAction
{

    /**
     * Returns the names of
     *
     * @return array
     */
    abstract function arguments();

}