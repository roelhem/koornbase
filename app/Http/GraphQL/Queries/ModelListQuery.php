<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:25
 */

namespace App\Http\GraphQL\Queries;


use EloquentFilter\Filterable;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class ModelListQuery extends Query
{

    /**
     * The name of the original type.
     *
     * @var string
     */
    protected $typeName;

    /**
     * The default limit of the amount of items per page.
     *
     * @var int $defaultLimit
     */
    protected $defaultLimit = 15;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ATTRIBUTES ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function attributes()
    {
        return [
            'name' => $this->name(),
            'description' => $this->description()
        ];
    }

    public function getTypeName()
    {
        if($this->typeName === null) {
            $this->typeName = $this->get('type');
        }
        return $this->typeName;
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
     * @throws
     */
    protected function query($args, $selectFields) {
        $modelClass = \GraphQL::getModelClassOfType($this->getTypeName());
        return call_user_func([$modelClass,'query']);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- QUERY CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc  */
    public function type()
    {
        return \GraphQL::paginate($this->getTypeName());
    }

    /**
     * Creates the config array.
     *
     * @return array
     */
    protected function getConfig()
    {
        return array_merge([
            'defaultLimit' => $this->defaultLimit
        ], [
            'type' => $this->getTypeName()
        ]);
    }

    /** @inheritdoc */
    public function args()
    {
        $config = $this->getConfig();

        return array_merge(
            \GraphQL::builder()->paginationArgs($config),
            \GraphQL::builder()->orderingArgs($config),
            \GraphQL::builder()->filterArgs($config),
            \GraphQL::builder()->searchingArgs($config)
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
        // Filter the authorized objects.
        $query = $this->applyAuthFilter($query);
        // Load the relations and fields
        $query->select($selectFields->getSelect());
        $query->with($selectFields->getRelations());

        // Apply the arguments
        $query = \GraphQL::builder()->filterResolve($query, $args);
        $query = \GraphQL::builder()->orderingResolve($query, $args);
        $query = \GraphQL::builder()->searchingResolve($query, $args);


        return \GraphQL::builder()->paginationInstance($query, $args);
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

}