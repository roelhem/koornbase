<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 14:11
 */

namespace Roelhem\GraphQL\Types;


abstract class MutationType extends ObjectType
{

    public $name = 'Mutation';

    public $description = 'The entry point for the queries that change the state of the main database.';

}