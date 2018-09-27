<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 05:58
 */

namespace Roelhem\GraphQL\Resolvers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;
use Roelhem\GraphQL\Resolvers\Middleware\QueryResultToPagination;
use Roelhem\GraphQL\Resolvers\Middleware\Validate\EnsureConnectionTypeReturn;
use Roelhem\GraphQL\Resolvers\Middleware\Validate\EnsureSourceInstanceOf;

class ModelConnectionResolver extends AbstractResolver
{

    public function __construct(array $middleware = [])
    {
        parent::__construct(array_merge([
            new EnsureSourceInstanceOf(Model::class),
            EnsureConnectionTypeReturn::class,
            QueryResultToPagination::class,
        ], $middleware));
    }

    /**
     * This method will be called after all the middleware-function were run for the first time. Afterward,
     * all the response changes of the middleware will be processed.
     *
     * @param Model $source
     * @param Fluent $args
     * @param mixed $context
     * @param ResolveStore $store
     * @return mixed
     * @throws
     */
    public function handle($source, $args, $context, ResolveStore $store)
    {
        return call_user_func([$source, $store->fieldName]);
    }
}