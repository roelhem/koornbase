<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:25
 */

namespace App\GraphQL\Queries;

use App\Enums\SortOrderDirection;
use App\GraphQL\Enums\SortOrderDirectionEnum;
use App\GraphQL\Queries\Traits\HasModelClassName;
use App\Services\Sorters\Traits\Sortable;
use EloquentFilter\Filterable;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Searchable;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class ModelListQuery extends Query
{

    use HasModelClassName;

    const ARG_TEXT_SEARCH = 'search';
    const ARG_ORDER_BY = 'orderBy';
    const ARG_ORDER_BY_FIELD = 'by';
    const ARG_ORDER_BY_DIRECTION = 'dir';

    /**
     * The default limit of the amount of items per page.
     *
     * @var int $defaultLimit
     */
    protected $defaultLimit = 15;

    public function attributes()
    {
        return [
            'name' => $this->name(),
            'description' => $this->description()
        ];
    }

    public function name() {
        return camel_case(str_plural($this->getTypeName()));
    }

    public function description() {
        return 'Gives a paginated list of `'.$this->getTypeName().'` models.';
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ABSTRACT METHODS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * A method that should return a query builder to query the models for this ModelListQuery.
     *
     * @param array $args
     * @param SelectFields $selectFields
     * @return Builder
     */
    protected function query($args, $selectFields) {
        return $this->getQuery();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- QUERY CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function type()
    {
        return \GraphQL::paginate($this->getTypeName());
    }

    /** @inheritdoc */
    public function args()
    {
        return array_merge(
            $this->paginationArgs(),
            $this->searchArgs(),
            $this->filterArgs(),
            $this->sortingArgs()
        );
    }

    /**
     * Resolves the query.
     *
     * @param mixed $root
     * @param array $args
     * @param SelectFields $selectFields
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function resolve($root, $args, SelectFields $selectFields)
    {
        // Get the query
        $query = $this->query($args, $selectFields);
        // Filter the authorized objects.
        $query = $this->applyAuthFilter($query);
        // Load the relations and fields
        $query->select($selectFields->getSelect());
        $query->with($selectFields->getRelations());

        // User filters
        $query = $this->applyFilters($query, $args);

        // Ordering
        $query = $this->setOrdering($query, $args);

        // Text-search filter
        $query = $this->applySearch($query, $args);

        return $this->createPagination($query, $args);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- AUTHORIZATION FILTER ------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * A function that creates a new instance of an RbacQueryFilter that belongs to this ModelListQuery.
     *
     * @return RbacQueryFilter
     */
    protected function getAuthFilter()
    {
        return new RbacQueryFilter($this->modelClass);
    }

    /**
     * Applies the filters from the RbacQueryFilter of this ModelListQuery to the provided query builder.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function applyAuthFilter($query)
    {
        return $this->getAuthFilter()->filter($query);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- USER FILTERS --------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The args for the user-filters.
     *
     * @return array
     */
    protected function filterArgs()
    {
        if(in_array(Filterable::class, class_uses_recursive($this->modelClass))) {
            return [
                'createdBefore' => [
                    'type' => \GraphQL::type('DateTime'),
                    'description' => 'Filters the models that were created before the provided moment.'
                ],
                'createdAfter' => [
                    'type' => \GraphQL::type('DateTime'),
                    'description' => 'Filters the models that were created after the provided moment.'
                ],
                'updatedBefore' => [
                    'type' => \GraphQL::type('DateTime'),
                    'description' => 'Filters the models that were last edited before the provided moment.'
                ],
                'updatedAfter' => [
                    'type' => \GraphQL::type('DateTime'),
                    'description' => 'Filters the models that were last edited after the provided moment.'
                ]
            ];
        } else {
            return [];
        }
    }

    /**
     * @param Builder $query
     * @param array $args
     * @return Builder
     */
    protected function applyFilters($query, $args)
    {
        if(!in_array(Filterable::class, class_uses_recursive($this->modelClass))) {
            return $query;
        }

        $filterKeys = array_keys($this->filterArgs());

        $filterArgs = collect($args)->filter(function($value, $key) use ($filterKeys) {
            return in_array($key, $filterKeys);
        })->all();

        return $query->filter($filterArgs);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- FULL TEXT SEARCH ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns if the subject model uses the Searchable trait, and thus supports full query search.
     *
     * @return bool
     */
    public function searchable() {
        return in_array(Searchable::class, class_uses_recursive($this->modelClass));
    }

    /**
     * Returns the argument definitions for the queries that have full-text search support.
     */
    public function searchArgs() {
        if($this->searchable()) {
            return [
                self::ARG_TEXT_SEARCH => [
                    'type' => Type::string(),
                    'description' => 'The input `string` for full-text searching trough all the items.'
                ]
            ];
        } else {
            return [];
        }
    }

    /**
     * Returns if the subject model uses the Searchable trait, and thus supports full query search.
     *
     * @param Builder $query
     * @param array $args
     * @return \Laravel\Scout\Builder|Builder
     */
    public function applySearch($query, $args) {
        $search = array_get($args, self::ARG_TEXT_SEARCH);

        if(empty($search)) {
            return $query;
        }

        if(!$this->searchable()) {
            return $query;
        }

        $callable = [$this->modelClass, 'search'];
        if(!is_callable($callable)) {
            throw new \LogicException("The model $this->modelClass is searchable, but the search method is missing.");
        }

        return call_user_func($callable, $search)->constrain($query);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SORTING -------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the argument definitions that are needed for the sorting of the list.
     *
     * @return array
     */
    protected function sortingArgs()
    {
        try {
            return [
                self::ARG_ORDER_BY => [
                    'type' => Type::listOf(\GraphQL::type($this->getTypeName() . '_sortRule')),
                    'description' => 'A list of sort rules to set the order of the list.',
                    'defaultValue' => [],
                ]
            ];
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * Adds the `orderBy`-statements to the provided query.
     *
     * @param Builder $query
     * @param array $args
     * @return Builder
     */
    protected function setOrdering($query, $args)
    {
        if(!in_array(Sortable::class, class_uses_recursive($this->modelClass))) {
            return $query;
        }

        $orderBy = array_get($args, self::ARG_ORDER_BY,[]);

        foreach($orderBy as $orderRule) {
            $field = array_get($orderRule, self::ARG_ORDER_BY_FIELD);
            if($field !== null) {
                $direction = array_get($orderRule, self::ARG_ORDER_BY_DIRECTION, SortOrderDirection::ASC());
                $query = $query->sortBy($field, $direction);
            }
        }

        return $query;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- PAGINATION ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the argument definitions that are needed for the pagination in the query.
     *
     * @return array
     */
    protected function paginationArgs()
    {
        return [
            'limit' => [
                'type' => Type::int(),
                'description' => 'The maximum amount of items per page.',
                'defaultValue' => $this->defaultLimit,
            ],
            'page' => [
                'type' => Type::int(),
                'description' => 'The number of the page to show.',
                'defaultValue' => 1
            ]
        ];
    }

    /**
     * Creates a new pagination object form the query based on the pagination arguments.
     *
     * @param Builder|\Laravel\Scout\Builder $query
     * @param array $args
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function createPagination($query, $args, $columns = ['*'])
    {
        // Get the arguments
        $per_page = array_get($args,'limit', $this->defaultLimit);
        $page = array_get($args, 'page', 1);
        // Create the pagination
        if($query instanceof Builder) {
            return $query->paginate($per_page, $columns,'page',$page);
        } else {
            return $query->paginate($per_page, 'page', $page);
        }
    }

}