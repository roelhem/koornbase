<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 21:49
 */

namespace App\Http\Controllers\Api;

use App\Contracts\Filters\FilterServiceContract;
use App\Http\Controllers\Controller as ParentController;
use App\Http\Resources\Api\Resource;
use App\Services\Sorters\Sorter;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\Paginator;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

/**
 * Class Controller
 *
 * A controller that has some additional functions to help controllers with parsing requests to the api.
 *
 * @package App\Http\Controllers\Api
 */
class Controller extends ParentController
{

    /**
     * @var string   The class name of the Model of this controller
     */
    protected $modelClass = Model::class;

    /**
     * @var string   The class name of the Resource of this controller
     */
    protected $resourceClass = Resource::class;

    /**
     * @var string    The class name of the Sorter of this controller
     */
    protected $sorterClass = Sorter::class;


    /**
     * The default index action. Shows a paginating list of all the models.
     *
     * @param Request $request
     * @return ResourceCollection
     * @throws
     */
    public function index(Request $request) {
        $modelClass = $this->modelClass;
        $resourceClass = $this->resourceClass;
        $sorter = resolve($this->sorterClass);



        // Initializing the query
        $query = $modelClass::query();

        // Apply the filter from the RBAC-graph.
        $queryFilter = new RbacQueryFilter($modelClass);
        $query = $queryFilter->filter($query);

        // Apply the filters from the request.
        $filters = $request->query('filter');
        if(is_array($filters)) {
            $query = $query->filter($filters);
        }

        // Apply the sorters
        $query = $sorter->addList($query, $this->getSortList($request));

        $query->with($this->getEagerLoadingRelations($request));

        $paginate = $this->getPaginate($query, $request);

        return $resourceClass::collection($paginate);
    }

    /**
     * A function that creates a paginate object and applies the settings that were send in the request.
     *
     * @param Builder $query
     * @param Request $request
     * @return LengthAwarePaginator
     */
    protected function getPaginate($query, Request $request) {
        return $query->paginate($request->query('per_page',15));
    }

    /**
     * A function that prepares a model before it is send as a response of an action.
     *
     * @param $model
     * @param Request $request
     * @return Resource
     */
    protected function prepare($model, Request $request) {
        $resourceClass = $this->resourceClass;
        $model->load($this->getEagerLoadingRelations($request));
        return new $resourceClass($model);
    }


    /**
     * Returns an array of sorting-settings from the request that can be used in a sorter.
     *
     * @param Request $request
     * @return array|string
     */
    protected function getSortList(Request $request) {
        $sort = $request->query('sort', []);

        if(is_string($sort)) {
            $sort = explode(',', $sort);
        }

        if(!is_array($sort)) {
            return [];
        }

        return $sort;
    }

    /**
     * Returns an array of relations where the request asked for.
     *
     * @param Request $request
     * @return array|null
     * @throws
     */
    protected function getAskedRelations(Request $request) {
        $with = $request->query('with', []);

        if(is_string($with)) {
            $with = explode(',', $with);
        }

        if(!is_array($with)) {
            $with = [];
        }

        foreach ($with as $relationName) {
            foreach ($this->getNestedRelationNames($relationName) as $nestedName) {
                if(!in_array($nestedName, $with)) {
                    $with[] = $nestedName;
                }
            }
        }

        return $with;
    }

    /**
     * @param string $relationName
     * @return array
     */
    protected function getNestedRelationNames($relationName) {
        $progress = [];
        $result = [];
        foreach (explode('.', $relationName) as $segment) {
            $progress[] = $segment;
            $result[] = implode('.', $progress);
        }
        return $result;
    }


    /**
     * Returns an array that can be used to eager-load the relations for which the user is authorized.
     *
     * @param Request $request
     * @return array
     */
    protected function getEagerLoadingRelations(Request $request) {
        $res = [];

        $relations = $this->getAskedRelations($request);

        foreach ($relations as $relation) {
            $res[$relation] = RbacQueryFilter::eagerLoadingConstraintClosure();
        }
        return $res;
    }

}