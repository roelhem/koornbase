<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 09:47
 */

namespace Roelhem\GraphQL\Resolvers\Middleware;


use GraphQL\Error\Error;
use GraphQL\Utils\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Roelhem\GraphQL\Resolvers\ResolveContext;
use Roelhem\GraphQL\Resolvers\ResolveStore;
use Roelhem\GraphQL\Types\Filters\FilterInputType;


class QueryApplyUserFilters
{

    /**
     * @param $source
     * @param $args
     * @param ResolveContext $context
     * @param ResolveStore $store
     * @param \Closure $next
     * @return mixed
     * @throws Error
     * @throws \Exception
     */
    public function __invoke($source, $args, ResolveContext $context, ResolveStore $store, \Closure $next)
    {
        // Check if the filters are valid
        $filterInput = array_get($args, 'filter');


        // Immediately pass on and return if no filterInput was given.
        if($filterInput === null) {
            return $next($source, $args, $context, $store);
        }

        // Retrieving the FilterInputType.
        $filterType = $store->field->getArg('filter')->getType();
        if(!($filterType instanceof FilterInputType)) {
            throw new Error("Can't find the FilterInputType!");
        }

        // Parse the filter input.
        $filterProps = $filterType->parseInput($filterInput);





        // PASS ONN....
        $result = $next($source, $args, $context, $store);

        // Checking the response type.
        if(!(
            $result instanceof Builder ||
            $result instanceof Relation ||
            $result instanceof \Illuminate\Database\Query\Builder
        )) {
            throw new Error("Resolver middleware ".self::class.' expected a query-builder as response, but got '.Utils::printSafe($result));
        }

        // Apply the filter(s)
        $result->filter($filterProps);


        // Return the resulting query.
        return $result;
    }

}