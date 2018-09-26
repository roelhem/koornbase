<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 21:41
 */

namespace Roelhem\GraphQL\Resolvers\Middleware;


use Roelhem\GraphQL\Resolvers\ResolveStore;

class HandleClosureResult
{

    public function __invoke($source, $args, $context, ResolveStore $store, \Closure $next)
    {
        $result = $next($source, $args, $context, $store);

        if($result instanceof \Closure) {
            return $result($source, $args, $context);
        } else {
            return $result;
        }
    }

}