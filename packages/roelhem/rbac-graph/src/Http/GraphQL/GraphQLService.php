<?php


namespace Roelhem\RbacGraph\Http\GraphQL;


use GraphQL;
use Roelhem\RbacGraph\Http\GraphQL\Enums\RbacNodeTypeEnum;
use Roelhem\RbacGraph\Http\GraphQL\Interfaces\RbacNodeInterface;
use Roelhem\RbacGraph\Http\GraphQL\Queries\RbacEdgesQuery;
use Roelhem\RbacGraph\Http\GraphQL\Queries\RbacNodesQuery;
use Roelhem\RbacGraph\Http\GraphQL\Queries\RbacPathsQuery;
use Roelhem\RbacGraph\Http\GraphQL\Types\RbacEdgeType;
use Roelhem\RbacGraph\Http\GraphQL\Types\RbacPathType;
use Roelhem\RbacGraph\Http\GraphQL\Types\RbacRoleType;
use Roelhem\RbacGraph\Http\GraphQL\Types\RbacNodeType;

class GraphQLService
{

    protected $types = [];

    /**
     * Returns an array with all the types that should be registered to the GraphQL.
     *
     * @return array
     */
    protected function getTypes()
    {
        return [
            'RbacNode' => RbacNodeInterface::class,

            'RbacRole' => RbacRoleType::class,
            'RbacDefaultNode' => RbacNodeType::class,

            'RbacNodeType' => RbacNodeTypeEnum::class,

            'RbacEdge' => RbacEdgeType::class,

            'RbacPath' => RbacPathType::class
        ];
    }


    /**
     * Returns the queries for the GraphQL-schema.
     *
     * @return array
     */
    protected function getQueries()
    {
        return [
            'nodes' => RbacNodesQuery::class,
            'paths' => RbacPathsQuery::class,
            'edges' => RbacEdgesQuery::class
        ];
    }


    /**
     * Returns the mutations for the GraphQL-schema.
     *
     * @return array
     */
    protected function getMutations()
    {
        return [];
    }


    /**
     * This method returns the instance of the Schema.
     *
     * @return array
     */
    public function getSchema()
    {
        return [
            'query' => $this->getQueries(),
            'mutations' => []
        ];
    }

    /**
     * Registers all the elements needed from the GraphQL interface.
     */
    public function register()
    {
        $this->registerTypes();
        $this->registerSchema();
    }

    /**
     * Registers the schema for the Rbac-graph to the GraphQL interface.
     */
    public function registerSchema()
    {
        $schema = $this->getSchema();
        GraphQL::addSchema('rbac', $schema);
    }

    /**
     * Registers the types for the rbac-graph to the GraphQL interface.
     */
    public function registerTypes()
    {
        GraphQL::addTypes($this->getTypes());
    }

}