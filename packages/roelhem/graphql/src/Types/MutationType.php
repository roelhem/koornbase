<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 14:11
 */

namespace Roelhem\GraphQL\Types;


use Roelhem\GraphQL\Fields\ActionField;

abstract class MutationType extends ObjectType
{

    public $name = 'Mutation';

    public $description = 'The entry point for the queries that change the state of the main database.';

    protected $actions = [];

    protected function fields()
    {
        return array_map(function($value) {

            return new ActionField($value);

        }, $this->actions);
    }
}