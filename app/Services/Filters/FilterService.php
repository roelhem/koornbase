<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-07-18
 * Time: 17:16
 */

namespace App\Services\Filters;


use App\Contracts\Filters\FilterProvider;
use App\Contracts\Filters\FilterServiceContract;
use App\Exceptions\Filters\FilterInvalidParametersException;
use App\Exceptions\Filters\FilterNotFoundException;
use App\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FilterService implements FilterServiceContract
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
    public function getClosures($request, $filterProvider)
    {
        $res = [];

        $filters = $request->get('filters',[]);

        foreach ($filters as $key => $params) {
            $res[$key] = $filterProvider->get($key, $params);
        }

        return $res;
    }

    /**
     * Applies an array of closures on the provided query.
     *
     * @param Builder $query
     * @param \Closure[] $closures
     * @return Builder
     */
    public function applyClosuresOn($query, $closures)
    {
        foreach ($closures as $closure) {
            $query = $closure($query);
        }
        return $query;
    }

    /**
     * Returns an instance of a FilterProvider that can provide the filters for the given $query.
     *
     * @param Builder $query
     * @return FilterProvider
     * @throws FilterNotFoundException
     */
    public function getProviderFor($query)
    {
        $model = $query->getModel();
        return $this->getProviderFromClassName(get_class($model));
    }

    /**
     * Returns an instance of FilterProvider that belongs to the provided className.
     *
     * @param string $className
     * @return FilterProvider
     * @throws FilterNotFoundException
     */
    public function getProviderFromClassName($className) {
        switch ($className) {
            case Person::class: return resolve(PersonFilterProvider::class);
            default:
                throw new FilterNotFoundException("Couldn't find a filter-provider for the class $className.");
        }
    }

    /**
     * Applies the filters in the request on the query.
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     * @throws FilterNotFoundException
     * @throws FilterInvalidParametersException
     */
    public function applyFiltersOn($query, $request)
    {
        $provider = $this->getProviderFor($query);
        $closures = $this->getClosures($request, $provider);
        return $this->applyClosuresOn($query, $closures);
    }
}