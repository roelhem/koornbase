<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 19:00
 */

namespace Roelhem\GraphQL\Resolvers;


use Illuminate\Support\Fluent;
use Roelhem\GraphQL\Resolvers\Middleware\FieldNameAlias;
use Roelhem\GraphQL\Resolvers\Middleware\HandleClosureResult;


class DefaultResolver extends AbstractResolver
{

    /**
     * DefaultResolver constructor.
     * @param array $middleware
     */
    public function __construct($middleware = [])
    {
        parent::__construct(array_merge([
            HandleClosureResult::class,
            FieldNameAlias::class,
        ], $middleware));
    }


    /**
     * @param mixed $source
     * @param Fluent $args
     * @param mixed $context
     * @param ResolveStore $store
     * @return mixed
     */
    public function handle($source, $args, $context, ResolveStore $store)
    {
        if(is_array($source) || $source instanceof \ArrayAccess) {
            return array_get($source, $store->fieldName);
        } elseif(is_object($source)) {
            return object_get($source, $store->fieldName);
        } else {
            return null;
        }
    }
}