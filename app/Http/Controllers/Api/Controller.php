<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 10:41
 */

namespace App\Http\Controllers\Api;



use App\Enums\SortOrderDirection;
use App\Http\Controllers\Controller as BaseController;
use App\Services\Sorters\Traits\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

abstract class Controller extends BaseController
{

    const PARAM_PAGE_LIMIT = 'limit';
    const PARAM_STAMPS = 'stamps';
    const PARAM_SEARCH = 'q';
    const PARAM_PAGE = 'page';
    const PARAM_ORDER_BY = 'order_by';

    /**
     * The className of the model that is managed.
     *
     * @var string|null
     */
    protected $modelClass;

    /**
     * The className of the model-resource that should be returned.
     *
     * @var string|null
     */
    protected $resourceClass;

    /**
     * @var string|null
     */
    protected $routeParameterName;

    /**
     * @var \Closure|null
     */
    protected $defaultEagerLoadClosure;

    /**
     * @var boolean|null
     */
    protected $textSearchable;

    protected $sortable;

    /**
     * The maximum amount of items on a page if no `limit` url-parameter was set.
     *
     * @var int
     */
    protected $defaultPageLimit = 15;

    /**
     * The highest amount of items that are allowed to be displayed at one page.
     *
     * @var int
     */
    protected $maxPageLimit = 100;

    /**
     * A list of relations that should be eager loaded in the `index` action.
     *
     * @var array
     */
    protected $eagerLoadForIndex = [];

    /**
     * A list of relations that should be eager loaded in the `show` action.
     *
     * @var array
     */
    protected $eagerLoadForShow = [];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ACTIONS -------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Action that gives a (paginated) list of models.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws
     */
    public function index(Request $request)
    {
        // Create the query
        $query = $this->createQuery();

        // Filter the models that are not authorized to be viewed by the current user
        $rbacFilter = new RbacQueryFilter($this->modelClass);
        $query = $rbacFilter->filter($query);

        // Eager-load the relations
        $query->with($this->createEagerLoadDefinition($this->eagerLoadForIndex));

        // Applying the filters
        $filterParams = $this->getFilterParams($request);
        if(count($filterParams) > 0) {
            $query->filter($filterParams);
        }

        // Check if a full-text-search should be preformed
        $searchText = $this->getSearchText($request);
        if($searchText === null) {

            // Apply the ordering rules.
            if($this->getSortable()) {
                foreach ($this->getOrderByRules($request) as $field => $direction) {
                    call_user_func([$query, 'sortBy'], $field, $direction);
                }
            }

            // Create the pagination object
            $pagination = $query->paginate($this->getPageLimit($request));
        } else {
            // Create the search query
            /** @var \Laravel\Scout\Builder $searchQuery */
            $searchQuery = call_user_func([$this->getModelClass(), 'search'], $searchText)->constrain($query);
            $pagination = $searchQuery->paginate($this->getPageLimit($request));
        }


        // Return the ResourceCollection result
        return $this->createResourceCollection($pagination);
    }

    /**
     * Action that gives the details of the selected model.
     *
     * @param Request $request
     * @return JsonResource
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ReflectionException
     */
    public function show(Request $request)
    {
        $model = $this->getModel($request);

        $this->authorize('view', $model);

        $model->load($this->createEagerLoadDefinition($this->eagerLoadForShow));

        return $this->createResource($model);
    }

    /**
     * Action that deletes a model
     *
     * @param Request $request
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ReflectionException
     */
    public function destroy(Request $request)
    {
        $model = $this->getModel($request);

        $this->authorize('delete', $model);

        $model->delete();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INSTANCE CREATOR HELPER METHODS -------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Creates a new query builder that belongs to the modelClass of this controller.
     *
     * @return Builder
     * @throws
     */
    protected function createQuery()
    {
        return call_user_func([$this->getModelClass(), 'query']);
    }

    /**
     * Creates a model-resource with the provided $model. The model-resource is of the class from the
     * `getResourceClass` method.
     *
     * @param $model
     * @return JsonResource
     * @throws
     */
    protected function createResource($model)
    {
        return app()->makeWith($this->getResourceClass(), ['resource' => $model]);
    }

    /**
     * @param array<Model>|LengthAwarePaginator $models
     * @return AnonymousResourceCollection
     * @throws
     */
    protected function createResourceCollection($models)
    {
        return call_user_func([$this->getResourceClass(), 'collection'], $models);
    }


    /**
     * Creates an array with the keys relation names and values closures to filter these relations.
     *
     * @param array<mixed,mixed> $array
     * @return array<String,Closure>
     */
    protected function createEagerLoadDefinition($array)
    {
        $res = [];

        foreach ($array as $key => $value) {
            if(is_integer($key)) {
                if(!is_string($value)) {
                    throw new \LogicException("Value of a integer keyed entry in a eager load array is not a string.");
                }
                $res[$value] = $this->getDefaultEagerLoadClosure();
            } else {
                if($value instanceof \Closure) {
                    $res[$key] = $value;
                } else {
                    $res[$value] = $this->getDefaultEagerLoadClosure();
                }
            }
        }
        return $res;
    }



    // ---------------------------------------------------------------------------------------------------------- //
    // ----- DYNAMIC (URL)-PARAMETER GETTERS -------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Retrieves and validates the value of the page limit.
     *
     * @param Request $request
     * @return integer
     */
    protected function getPageLimit(Request $request)
    {
        $limit = $request->query(self::PARAM_PAGE_LIMIT, $this->defaultPageLimit);

        if($limit === null) {
            abort(400, 'The limit has to be an integer or not set at all.');
        }

        if(is_string($limit)) {
            if(!ctype_digit($limit)) {
                abort(400, 'The limit has to be an integer.');
            }
            $limit = intval($limit);
        }

        if(!is_integer($limit)) {
            throw new \InvalidArgumentException("Can't convert the provided limit to an integer");
        }

        if($limit <= 0) {
            abort(400, 'The limit has to be bigger than 0.');
        }

        if($limit > $this->maxPageLimit) {
            abort(400, 'The limit is not allowed to be higher than '.$this->maxPageLimit.'.');
        }

        return $limit;
    }

    /**
     * @param Request $request
     * @return Model
     * @throws \Exception
     * @throws \ReflectionException
     */
    protected function getModel(Request $request)
    {
        $routeParamName = $this->getRouteParameterName();
        $routeParam = $request->route($routeParamName);

        if(empty($routeParam)) {
            abort(404, 'Model not found. ');
        }

        $modelClass = $this->getModelClass();

        if($routeParam instanceof Model && is_a($routeParam, $modelClass)) {
            return $routeParam;
        }

        return call_user_func([$modelClass, 'findOrFail'], $routeParam);
    }

    /**
     * Returns the parameters that may control filters on the search result.
     *
     * @param Request $request
     * @return array
     */
    protected function getFilterParams(Request $request)
    {
        $reservedParams = [
            self::PARAM_PAGE,
            self::PARAM_PAGE_LIMIT,
            self::PARAM_STAMPS,
            self::PARAM_SEARCH,
            self::PARAM_ORDER_BY,
        ];

        $res = [];
        foreach ($request->query() as $key => $value) {
            if(!in_array($key, $reservedParams)) {
                $res[camel_case($key)] = $value;
            }
        }
        return $res;
    }

    /**
     * Returns the text that should be used in a full-text-search. If null is returned, no full-text-search should
     * be preformed.
     *
     * @param Request $request
     * @return null|string
     * @throws \Exception
     */
    protected function getSearchText(Request $request)
    {
        $q = $request->query(self::PARAM_SEARCH);

        if(!is_string($q) || !$this->getTextSearchable()) {
            return null;
        }

        return $q;
    }

    /**
     * Returns an array with the order rules that should be applied.
     *
     * @param Request $request
     * @return array<string,SortOrderDirection>
     */
    protected function getOrderByRules(Request $request)
    {
        $orderBy = $request->query(self::PARAM_ORDER_BY);

        if($orderBy === null) {
            $orderBy = [];
        }

        if(is_string($orderBy)) {
            $orderBy = explode(',',$orderBy);
        }

        if(!is_array($orderBy)) {
            abort(400, 'Invalid '.self::PARAM_ORDER_BY.' argument.');
        }

        $res = [];
        foreach ($orderBy as $key => $value) {
            if(is_string($key)) {
                $field = $key;
                $dir = $value;
            } else {
                $pieces = explode(':', $value);
                $field = array_get($pieces, 0);
                $dir = array_get($pieces, 1);
            }
            if(!empty($field)) {
                $res[strval($field)] = SortOrderDirection::by($dir);
            }
        }
        return $res;
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CLASS-NAME GETTERS --------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Returns the name of the model-class that this controller manages.
     *
     * @return string
     * @throws
     */
    protected function getModelClass()
    {
        if($this->modelClass === null) {
            $reflection = new \ReflectionClass($this);
            $shortName = $reflection->getShortName();
            $modelShortName = str_before($shortName, 'Controller');
            $modelClass = "App\\".$modelShortName;
            if(class_exists($modelClass) && is_subclass_of($modelClass, Model::class)) {
                $this->modelClass = $modelClass;
            } else {
                throw new \Exception("Can't find the class with name $modelClass.");
            }
        }

        return $this->modelClass;
    }

    /**
     * Returns the short name of the model.
     *
     * @return string
     * @throws \Exception
     * @throws \ReflectionException
     */
    protected function getModelShortName()
    {
        $reflection = new \ReflectionClass($this->getModelClass());
        return $reflection->getShortName();
    }

    /**
     * Returns the class name of the model-resource that should be used as the response of the actions in this
     * controller.
     *
     * @return string
     * @throws \Exception
     * @throws \ReflectionException
     */
    protected function getResourceClass()
    {
        if($this->resourceClass === null) {
            $modelShortName = $this->getModelShortName();
            $resourceShortName = $modelShortName.'Resource';
            $resourceClass = "App\\Http\\Resources\\Api\\".$resourceShortName;
            if(class_exists($resourceClass) && is_subclass_of($resourceClass, JsonResource::class)) {
                $this->resourceClass = $resourceClass;
            } else {
                throw new \Exception("Can't find the resource with className $resourceClass.");
            }
        }

        return $this->resourceClass;
    }

    /**
     * Returns the name of the route parameter that contains the reference to the model that is selected.
     *
     * @return string
     * @throws \Exception
     * @throws \ReflectionException
     */
    protected function getRouteParameterName()
    {
        if($this->routeParameterName === null) {
            $this->routeParameterName = snake_case($this->getModelShortName());
        }

        return $this->routeParameterName;
    }

    /**
     * Returns wheter or not this controller should provide text-searchable functionality.
     *
     * @return bool
     * @throws \Exception
     */
    protected function getTextSearchable()
    {
        if($this->textSearchable === null) {
            $this->textSearchable = in_array(Searchable::class, class_uses_recursive($this->getModelClass()));
        }

        return $this->textSearchable;
    }

    /**
     * Returns if the controller should provide sortable functionality.
     *
     * @return bool
     * @throws \Exception
     */
    protected function getSortable()
    {
        if($this->sortable === null) {
            $this->sortable = in_array(Sortable::class, class_uses_recursive($this->getModelClass()));
        }

        return $this->sortable;
    }


    /**
     * Returns the default closure that should be added to eager loading relations.
     *
     * @return \Closure
     */
    protected function getDefaultEagerLoadClosure()
    {
        if($this->defaultEagerLoadClosure === null) {
            $this->defaultEagerLoadClosure = RbacQueryFilter::eagerLoadingConstraintClosure();
        }

        return $this->defaultEagerLoadClosure;
    }

}