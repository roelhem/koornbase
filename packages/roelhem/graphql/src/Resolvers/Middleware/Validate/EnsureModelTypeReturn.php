<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 23:20
 */

namespace Roelhem\GraphQL\Resolvers\Middleware\Validate;


use GraphQL\Error\Error;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;
use Roelhem\GraphQL\Contracts\ModelTypeContract;
use Roelhem\GraphQL\Resolvers\ResolveContext;
use Roelhem\GraphQL\Resolvers\ResolveStore;
use Roelhem\GraphQL\Types\ModelType;

class EnsureModelTypeReturn
{
    /**
     * @param mixed $source
     * @param Fluent $args
     * @param ResolveContext $context
     * @param ResolveStore $store
     * @param \Closure $next
     * @return mixed
     * @throws Error
     */
    public function __invoke($source, $args, ResolveContext $context, ResolveStore $store, \Closure $next)
    {

        if(!($store->returnType instanceof ModelTypeContract)) {
            throw new Error("The responseType of this resolver has to be an instance of ".ModelTypeContract::class.".");
        }

        $modelClass = $store->returnType->getModelClass();

        if(!is_subclass_of($modelClass, Model::class)) {
            throw new Error("The modelClass of the ".ModelType::class." has to be an instance of ".Model::class.". Value: $modelClass.");
        }

        $result = $next($source, $args, $context, $store);

        if(is_a($result, $modelClass)) {
            return $result;
        }
        return null;



    }
}