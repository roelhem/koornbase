<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:25
 */

namespace App\GraphQL\Queries;

use App\GraphQL\Enums\SortOrderDirectionEnum;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

abstract class ModelListQuery extends Query
{

    /**
     * The name of the type that should represent the list.
     *
     * @var string $typeName
     */
    protected $typeName;

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
    abstract protected function query($args, $selectFields);

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- QUERY CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function type()
    {
        if($this->typeName === null) {
            throw new \LogicException("Can't find a \$typeName for this ModelListQuery. ");
        }

        return \GraphQL::paginate($this->typeName);
    }

    /** @inheritdoc */
    public function args()
    {
        return array_merge(
            $this->paginationArgs(),
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
        $query = $this->query($args, $selectFields);

        $query = $this->setOrdering($query, $args);

        return $this->createPagination($query, $args);
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