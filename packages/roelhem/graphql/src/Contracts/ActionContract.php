<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:31
 */

namespace Roelhem\GraphQL\Contracts;

use GraphQL\Type\Definition\Type;
use \Roelhem\Actions\Contracts\ActionContract as BaseActionContract;

interface ActionContract extends BaseActionContract
{

    /**
     * Method that should return the return type of the Action.
     *
     * @return Type
     */
    public function type();

}