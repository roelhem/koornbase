<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 06:35
 */

namespace Roelhem\GraphQL\Resolvers;


use App\Person;
use Illuminate\Support\Fluent;
use Roelhem\GraphQL\Resolvers\Middleware\QueryResultToPagination;
use Roelhem\GraphQL\Resolvers\Middleware\Validate\EnsureConnectionTypeReturn;
use Roelhem\GraphQL\Types\Connections\ConnectionType;
use Roelhem\GraphQL\Types\ModelType;

class ModelListResolver extends AbstractResolver
{

    public function __construct(array $middleware = [])
    {
        parent::__construct(array_merge([
            EnsureConnectionTypeReturn::class,
            QueryResultToPagination::class,
        ], $middleware));
    }

    /**
     * This method will be called after all the middleware-function were run for the first time. Afterward,
     * all the response changes of the middleware will be processed.
     *
     * @param mixed $source
     * @param Fluent $args
     * @param mixed $context
     * @param ResolveStore $store
     * @return mixed
     * @throws
     */
    public function handle($source, $args, $context, ResolveStore $store)
    {
        /** @var ConnectionType $returnType */
        $returnType = $store->returnType;
        /** @var ModelType $targetType */
        $targetType = $returnType->getEdgeType()->getField('node')->getType();

        $modelClass = $targetType->modelClass;

        return call_user_func([$modelClass, 'query']);
    }
}