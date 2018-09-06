<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 07:49
 */

namespace App\Services\GraphQL;


use App\Enums\SortOrderDirection;
use GraphQL\Type\Definition\Type;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Builder;
use Laravel\Scout\Searchable;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class GraphQLBuilder
{
    const ARG_TEXT_SEARCH = 'search';
    const ARG_ORDER_BY = 'orderBy';
    const ARG_ORDER_BY_FIELD = 'by';
    const ARG_ORDER_BY_DIRECTION = 'dir';
    const ARG_PAGINATION_ITEM_LIMIT = 'limit';
    const ARG_PAGINATION_PAGE = 'page';

    protected $eagerQuery;

    public function __construct()
    {
        $this->eagerQuery = RbacQueryFilter::eagerLoadingContraintGraphQLClosure();
    }


    public function authFilterQuery()
    {
        return $this->eagerQuery;
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- FIELDS --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the array that is needed to configure a field that uses pagination.
     *
     * @param array $config
     * @return array
     * @throws
     */
    public function relationField($config)
    {
        // The result array
        $result = [];

        // Getting the right type
        $typeName = array_get($config, 'type');
        $result['type'] = \GraphQL::paginate($typeName);

        // Getting the name
        $name = array_get($config, 'name');

        // Getting the right relation
        $relation = array_get($config, 'relation', $name);
        if(array_has($config, 'resolve')) {
            $queryClosure = $config['resolve'];
        } else {
            $queryClosure = function(Model $model, $args) use ($relation) {
                return call_user_func([$model, $relation]);
            };
        }


        // Setting the arguments
        $result['args'] = array_merge(
            $this->paginationArgs($config),
            $this->orderingArgs($config),
            $this->searchingArgs($config),
            $this->filterArgs($config)
        );

        // Setting the additional queries
        $result['query'] = $this->authFilterQuery();


        // Setting the resolve function
        $result['resolve'] = function($root, $args) use ($queryClosure) {
            // Getting the query
            $query = $queryClosure($root, $args);

            // Apply the filtering
            $query = $this->filterResolve($query, $args);

            // Apply the searching
            $query = $this->searchingResolve($query, $args);

            // Apply the ordering if there was no searching
            if(!($query instanceof Builder)) {
                $query = $this->orderingResolve($query, $args);
            }

            // Returning a new pagination instance
            return $this->paginationInstance($query, $args);
        };



        // Return the result
        return array_merge($result, array_except($config, ['type','resolve']));
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- FILTERS -------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Gives the arguments needed for a filter-field.
     *
     * @param array $config
     * @return array
     */
    public function filterArgs($config = [])
    {
        $typeName = array_get($config,'type');

        try {
            $filterType = \GraphQL::type($typeName.'_filter');
            return [
                'filter' => [
                    'type' => $filterType,
                    'description' => 'Some settings to make filter items from the list.'
                ]
            ];
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * Resolves the filters
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $args
     * @return Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function filterResolve($query, $args)
    {
        $filterArgs = array_get($args, 'filter');
        if(!is_array($filterArgs) || count($filterArgs) <= 0) {
            return $query;
        }
        return $query->filter($filterArgs);
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SEARCHING ------------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the arguments needed for a full-text-search.
     *
     * @param array $config
     * @return array
     * @throws \Exception
     */
    public function searchingArgs($config = [])
    {
        $type = \GraphQL::getModelClassOfType(array_get($config, 'type'));
        if(!$this->searchingAvailable($type)) {
            return [];
        }
        return [
            self::ARG_TEXT_SEARCH => [
                'type' => Type::string(),
                'description' => 'The input `string` for full-text searching items.'
            ]
        ];
    }

    /**
     * Resolves the full-text-search.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $args
     * @return Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function searchingResolve($query, $args)
    {
        $searchString = array_get($args,self::ARG_TEXT_SEARCH);
        $modelClass = get_class($query->getModel());

        if(!$this->searchingActive($searchString, $modelClass)) {
            return $query;
        }

        return call_user_func([$modelClass, 'search'], $searchString)->constrain($query);
    }

    /**
     * Determines if the full-text-searching is active.
     *
     * @param string $searchString
     * @param string $modelClass
     * @return bool
     */
    protected function searchingActive($searchString, $modelClass)
    {
        if(empty($searchString)) {
            return false;
        }

        return $this->searchingAvailable($modelClass);
    }

    /**
     * Determines if you can search on the provided model.
     *
     * @param string $modelClass
     * @return bool
     */
    protected function searchingAvailable($modelClass)
    {
        return in_array(Searchable::class, class_uses_recursive($modelClass));
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ORDERING ------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * The arguments that are needed for fields that can be ordered.
     *
     * @param array $config
     * @return array
     */
    public function orderingArgs($config = [])
    {
        $typeName = array_get($config, 'type');
        $type = Type::listOf(\GraphQL::sortRule($typeName));

        return [
            self::ARG_ORDER_BY => [
                'type' => $type,
                'description' => 'A list of sort rules to set the order of the list.',
                'defaultValue' => array_get($config, 'defaultOrdering', []),
            ]
        ];
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderingResolve($query, $args)
    {
        $orderRules = array_get($args, self::ARG_ORDER_BY, []);
        foreach ($orderRules as $orderRule) {
            $field = array_get($orderRule, self::ARG_ORDER_BY_FIELD);
            $direction = array_get($orderRule, self::ARG_ORDER_BY_DIRECTION, SortOrderDirection::default());
            $query->sortBy($field, $direction);
        }
        return $query;
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- PAGINATION ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the arguments that are needed for fields that use pagination.
     *
     * @param array $config
     * @return array
     */
    public function paginationArgs($config = [])
    {
        return [
            self::ARG_PAGINATION_ITEM_LIMIT => [
                'type' => Type::int(),
                'description' => 'The maximum amount of items per page.',
                'defaultValue' => array_get($config, 'defaultLimit', 5),
            ],
            self::ARG_PAGINATION_PAGE => [
                'type' => Type::int(),
                'description' => 'The number of the page to show.',
                'defaultValue' => array_get($config,'defaultPage', 1)
            ]
        ];
    }

    /**
     * Creates a new instance of a pagination based on the provided query and arguments.
     *
     * @param Builder|\Illuminate\Database\Eloquent\Builder $query
     * @param array $args
     * @param int $defaultLimit
     * @param int $defaultPage
     * @param array $columns
     * @param string $pageName
     * @return LengthAwarePaginator
     */
    public function paginationInstance($query, $args, $defaultLimit = 5, $defaultPage = 1, $columns = ['*'], $pageName = 'page')
    {
        // Collecting the arguments
        $per_page = array_get($args,self::ARG_PAGINATION_ITEM_LIMIT, $defaultLimit);
        $page = array_get($args, self::ARG_PAGINATION_PAGE, $defaultPage);

        // Creating the pagination
        if($pageName instanceof Builder) {
            return $query->paginate($per_page, $pageName, $page);
        } else {
            return $query->paginate($per_page, $columns, $pageName, $page);
        }
    }



}