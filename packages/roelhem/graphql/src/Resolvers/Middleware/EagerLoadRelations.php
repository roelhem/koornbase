<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 15:42
 */

namespace Roelhem\GraphQL\Resolvers\Middleware;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Roelhem\GraphQL\Resolvers\ResolveContext;
use Roelhem\GraphQL\Resolvers\ResolveStore;

class EagerLoadRelations
{

    /**
     * @param $source
     * @param $args
     * @param ResolveContext $context
     * @param ResolveStore $store
     * @param \Closure $next
     * @return mixed
     */
    public function __invoke($source, $args, ResolveContext $context, ResolveStore $store, \Closure $next) {


        $result = $next($source, $args, $context, $store);

        $relations = [];
        foreach ($store->fieldNodeIterator() as $fieldHelper) {
            $eagerLoad = array_get($fieldHelper->field->config,'eagerLoad');
            if($eagerLoad !== null) {
                $relations[] = $eagerLoad;
            }
        }

        if($result instanceof Builder || $result instanceof Relation || $result instanceof \Illuminate\Database\Query\Builder) {
            $result->with($relations);
        }

        return $result;

    }

}