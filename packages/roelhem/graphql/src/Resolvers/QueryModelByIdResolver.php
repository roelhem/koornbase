<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 23:16
 */

namespace Roelhem\GraphQL\Resolvers;


use Roelhem\GraphQL\Resolvers\Middleware\Validate\EnsureModelTypeReturn;
use Roelhem\GraphQL\Types\ModelType;

class QueryModelByIdResolver extends AbstractResolver
{


    public function __construct(array $middleware = [])
    {
        parent::__construct(array_merge(
            [EnsureModelTypeReturn::class],
            $middleware
        ));
    }


    public function handle($source, $args, ResolveContext $context, ResolveStore $store)
    {
        /** @var ModelType $returnType */
        $returnType = $store->returnType;

        return call_user_func([$returnType->modelClass, 'find'], $args->get('id'));
    }
}