<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 20:55
 */

namespace Roelhem\GraphQL\Resolvers\Middleware;


use Roelhem\GraphQL\Resolvers\ResolveContext;
use Roelhem\GraphQL\Resolvers\ResolveStore;

class FieldNameAlias
{

    public function __invoke($source, $args, ResolveContext $context, ResolveStore $store, \Closure $next)
    {

        $field = $store->field;
        $newName = array_get($field->config, 'alias', $store->fieldName);
        $store->fieldName = $newName;

        return $next($source, $args, $context, $store);

    }
}