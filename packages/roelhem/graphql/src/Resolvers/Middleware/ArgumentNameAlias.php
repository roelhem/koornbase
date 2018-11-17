<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 00:35
 */

namespace Roelhem\GraphQL\Resolvers\Middleware;

use Roelhem\GraphQL\Resolvers\ResolveContext;
use Roelhem\GraphQL\Resolvers\ResolveStore;

class ArgumentNameAlias
{

    public function __invoke($source, $args, ResolveContext $context, ResolveStore $store, \Closure $next)
    {

        $field = $store->field;

        foreach ($field->args as $arg) {
            if(array_has($arg->config, 'alias')) {
                $name = $arg->name;
                $alias = array_get($arg->config,'alias');
                if(array_has($args,$name)) {
                    $args[$alias] = array_get($args,$name);
                }
            }
        }

        return $next($source, $args, $context, $store);

    }

}