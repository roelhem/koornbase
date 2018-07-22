<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:25
 */

namespace App\GraphQL\Queries;

use App\GraphQL\Enums\SortOrderDirectionEnum;
use App\GraphQL\Queries\Traits\HasModelClassName;
use EloquentFilter\Filterable;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class ModelListQuery extends Query
{

    use HasModelClassName;

    /**
     * The default limit of the amount of items per page.
     *
     * @var int $defaultLimit
     */
    protected $defaultLimit = 15;

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
            $this->sortingArgs(),
            $this->filterArgs()
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
    // ----- SORTING -------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the argument definitions that are needed for the sorting of the list.
     *
     * @return array
     */
    protected function sortingArgs()
    {
        return [
            'orderBy' => [
                'type' => Type::listOf(\GraphQL::type('SortOrder')),
                'description' => 'A list of SortOrder config-objects to set the order of the list.',
                'defaultValue' => [],
            ]
        ];
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
        $orderBy = array_get($args, 'orderBy',[]);

        foreach($orderBy as $orderRule) {
            $field = array_get($orderRule, 'field');
            if($field !== null) {
                $direction = array_get($orderRule, 'direction', SortOrderDirectionEnum::ASC);
                $query = $query->orderBy($field, $direction);
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
     * @param Builder $query
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
        return $query->paginate($per_page, $columns,'page',$page);
    }

}