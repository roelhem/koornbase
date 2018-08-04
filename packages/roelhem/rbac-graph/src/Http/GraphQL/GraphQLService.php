<?php


namespace Roelhem\RbacGraph\Http\GraphQL;


use GraphQL;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Http\GraphQL\Enums\RbacNodeTypeEnum;
use Roelhem\RbacGraph\Http\GraphQL\Interfaces\RbacAssignableNodeInterface;
use Roelhem\RbacGraph\Http\GraphQL\Interfaces\RbacGraphInterface;
use Roelhem\RbacGraph\Http\GraphQL\Interfaces\RbacNodeInterface;
use Roelhem\RbacGraph\Http\GraphQL\Queries\RbacEdgesQuery;
use Roelhem\RbacGraph\Http\GraphQL\Queries\RbacNodesQuery;
use Roelhem\RbacGraph\Http\GraphQL\Queries\RbacPathsQuery;
use Roelhem\RbacGraph\Http\GraphQL\Types\PostgresRbacGraphType;
use Roelhem\RbacGraph\Http\GraphQL\Types\RbacAssignmentType;
use Roelhem\RbacGraph\Http\GraphQL\Types\RbacEdgeType;
use Roelhem\RbacGraph\Http\GraphQL\Types\RbacPathType;

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
        $types = [
            'RbacGraph' => RbacGraphInterface::class,
            'PostgresRbacGraph' => PostgresRbacGraphType::class,

            'RbacNode' => RbacNodeInterface::class,
            'RbacAssignableNode' => RbacAssignableNodeInterface::class,

            'RbacNodeType' => RbacNodeTypeEnum::class,

            'RbacEdge' => RbacEdgeType::class,
            'RbacAssignment' => RbacAssignmentType::class,

            'RbacPath' => RbacPathType::class
        ];

        foreach (NodeType::getEnumerators() as $enumerator) {
            $types[$enumerator->getGraphQLTypeName()] = $enumerator->getGraphQLType();
        }

        return $types;
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