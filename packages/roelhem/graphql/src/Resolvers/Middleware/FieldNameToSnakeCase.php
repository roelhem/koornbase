<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 20:55
 */

namespace Roelhem\GraphQL\Resolvers\Middleware;


use Roelhem\GraphQL\Resolvers\ResolveStore;

class FieldNameToSnakeCase
{

    public function __invoke($source, $args, $context, ResolveStore $store, \Closure $next)
    {

        $fieldName = $store->fieldName;
        $store->fieldName = snake_case($fieldName);

        return $next($source, $args, $context, $store);

    }
}