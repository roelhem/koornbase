<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 03:02
 */

namespace Roelhem\GraphQL\Facades;


use Roelhem\GraphQL\Contracts\TypeLoaderContract;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Facade;

/**
 * Class GraphQL
 * @package Roelhem\GraphQL\Facades
 *
 * @method static Type type(string $def)
 * @method static TypeLoaderContract typeLoader()
 */
class GraphQL extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Roelhem\GraphQL\GraphQL::class;
    }
}