<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 09:57
 */

namespace Roelhem\GraphQL\Resolvers\Middleware;


use GraphQL\Error\Error;
use GraphQL\Error\UserError;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Roelhem\GraphQL\Enums\PaginationType;
use Roelhem\GraphQL\Paginators\OffsetPaginator;
use Roelhem\GraphQL\Resolvers\ResolveStore;

class QueryResultToPagination
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
        // Check if there aren't any extra pagination parameters.
        $countArgs = 0;
        $pagArgs = ['after','offset','page'];
        foreach ($pagArgs as $pagArg) {
            if($args->get($pagArg) !== null) {
                $countArgs++;
            }
        }
        if($countArgs > 1) {
            throw new UserError("Only one type of pagination is allowed at the same time. Therefore, you can use only just one of the 'after','offset' or 'page' arguments.");
        }

        // Get the pagination type.
        $paginationType = PaginationType::OFFSET_BASED();
        if($args->get('page') !== null) {
            $paginationType = PaginationType::PAGE_BASED();
        }
        if($args->get('after') !== null) {
            $paginationType = PaginationType::CURSOR_BASED();
        }
        $store['paginationType'] = $paginationType;


        // RETRIEVE THE RESULT
        $result = $next($source, $args, $context, $store);


        if($result instanceof Builder || $result instanceof \Illuminate\Database\Query\Builder || $result instanceof Relation) {
            return $paginationType->fromQuery($result, $args);
        } else {
            return null;
        }
    }
}