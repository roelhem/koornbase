<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 22:55
 */

namespace Roelhem\GraphQL\Resolvers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;
use Roelhem\GraphQL\Resolvers\Middleware\FieldNameAlias;
use Roelhem\GraphQL\Resolvers\Middleware\HandleClosureResult;
use Roelhem\GraphQL\Resolvers\Middleware\Validate\EnsureSourceInstanceOf;

class ModelTypeResolver extends AbstractResolver
{

    /**
     * ModelTypeResolver constructor.
     * @param array $middleware
     */
    public function __construct($middleware = [])
    {
        parent::__construct(array_merge([
            HandleClosureResult::class,
            FieldNameAlias::class,
            new EnsureSourceInstanceOf(Model::class),
        ], $middleware));
    }

    /**
     * This method will be called after all the middleware-function were run for the first time. Afterward,
     * all the response changes of the middleware will be processed.
     *
     * @param Model $source
     * @param Fluent $args
     * @param ResolveContext $context
     * @param ResolveStore $store
     * @return mixed
     */
    public function handle($source, $args, ResolveContext $context, ResolveStore $store)
    {
        return $source->getAttribute($store->fieldName);
    }
}