<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-07-18
 * Time: 17:03
 */

namespace App\Contracts\Filters;
use App\Exceptions\Filters\FilterInvalidParametersException;
use App\Exceptions\Filters\FilterNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


/**
 * Contract FilterService
 *
 * A contract for the objects that help to apply the filters to the queries.
 *
 * @package App\Contracts\Filters
 */
interface FilterServiceContract
{

    /**
     * Returns the closures of the filters from the request. These closures are provided by the $filterProvider.
     *
     * @param Request $request
     * @param FilterProvider $filterProvider
     * @return \Closure[]
     * @throws FilterNotFoundException
     * @throws FilterInvalidParametersException
     */
    public function getClosures($request, $filterProvider);

    /**
     * Applies an array of closures on the provided query.
     *
     * @param Builder $query
     * @param \Closure[] $closures
     * @return Builder
     */
    public function applyClosuresOn($query, $closures);

    /**
     * Returns an instance of a FilterProvider that can provide the filters for the given $query.
     *
     * @param Builder $query
     * @return FilterProvider
     * @throws FilterNotFoundException
     */
    public function getProviderFor($query);

    /**
     * Applies the filters in the request on the query.
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     * @throws FilterNotFoundException
     * @throws FilterInvalidParametersException
     */
    public function applyFiltersOn($query, $request);

}