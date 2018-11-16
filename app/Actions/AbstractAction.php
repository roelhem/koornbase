<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-10-18
 * Time: 06:49
 */

namespace App\Actions;


use Roelhem\Actions\Actions\Action;
use Roelhem\GraphQL\Contracts\ActionContract as GraphQLActionContract;
use Roelhem\GraphQL\Facades\GraphQL;

abstract class AbstractAction extends Action implements GraphQLActionContract
{

    /** @var string The return type of the action */
    protected $type;

    /**
     * Returns the type of this action, based on the value of the `$type` property.
     *
     * @return \GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type($this->type);
    }

}