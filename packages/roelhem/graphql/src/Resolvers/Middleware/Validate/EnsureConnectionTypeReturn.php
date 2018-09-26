<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 06:36
 */

namespace Roelhem\GraphQL\Resolvers\Middleware\Validate;

use GraphQL\Error\Error;
use Illuminate\Support\Fluent;
use Roelhem\GraphQL\Resolvers\ResolveStore;
use Roelhem\GraphQL\Types\Connections\ConnectionType;

class EnsureConnectionTypeReturn
{

    /**
     * @param mixed $source
     * @param Fluent $args
     * @param $context
     * @param ResolveStore $store
     * @param \Closure $next
     * @return mixed
     * @throws Error
     */
    public function __invoke($source, $args, $context, ResolveStore $store, \Closure $next)
    {
        if(!($store->returnType instanceof ConnectionType)) {
            throw new Error("The responseType of this resolver has to be an instance of ".ConnectionType::class);
        }

        return $next($source, $args, $context, $store);

    }

}