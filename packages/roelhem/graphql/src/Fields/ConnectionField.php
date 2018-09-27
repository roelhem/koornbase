<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 23:45
 */

namespace Roelhem\GraphQL\Fields;


use GraphQL\Error\InvariantViolation;
use Roelhem\GraphQL\Contracts\ModelTypeContract;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Resolvers\ModelConnectionResolver;
use Roelhem\GraphQL\Resolvers\QueryModelListResolver;
use Roelhem\GraphQL\Types\Connections\ConnectionType;
use Roelhem\GraphQL\Types\Filters\FilterInputType;
use Roelhem\GraphQL\Types\ModelType;
use Roelhem\GraphQL\Types\OrderBy\OrderByInputType;
use Roelhem\GraphQL\Types\QueryType;

class ConnectionField extends Field
{

    protected $toType;

    protected $fromType;

    /**
     * ConnectionField constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // Getting the toType.
        $this->toType = array_get($config, 'toType', array_get($config,'to'));

        // Getting the fromType.
        $this->fromType = array_get($config, 'fromType', array_get($config,'from'));



        parent::__construct($config);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- TYPE ----------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @var ConnectionType|null */
    protected $type;

    /**
     * Returns the ConnectionType of this ConnectionField.
     *
     * @return ConnectionType
     */
    public function type()
    {
        if($this->type === null) {
            $type = array_get($this->fromType, 'type');
            if($type instanceof ConnectionType) {
                $this->type = $type;
            } elseif(is_string($type)) {
                $this->type = resolve($type);
            } elseif(is_array($type)) {
                $this->type = new ConnectionType(array_merge([
                    'connectionName' => $this->name(),
                    'toType' => $this->toType,
                    'fromType' => $this->fromType,
                ],$type));
            } else {
                $this->type = new ConnectionType([
                    'connectionName' => $this->name(),
                    'toType' => $this->toType,
                    'fromType' => $this->fromType,
                ]);
            }
        }
        return $this->type;
    }

    /**
     * Returns the name of the connection-type of this field.
     */
    public function typeName()
    {
        return $this->type()->name;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns if the $fromType of this ConnectionField can be seen as the Query-type.
     *
     * @return boolean
     */
    public function fromQueryType()
    {
        return $this->fromType instanceof QueryType || $this->fromType === 'Query' || $this->fromType === null;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- DEFAULT VALUES ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the name of the field.
     *
     * @return string
     */
    public function name()
    {
        $name = array_get($this->config,'name');
        if(empty($name)) {
            $name = camel_case(str_plural($this->toType));
        }
        return $name;
    }


    /**
     * Returns a string description
     *
     * @return string
     */
    public function description()
    {
        $description = array_get($this->config, 'description');
        if(empty($description)) {
            if($this->fromQueryType()) {
                return "Gives a paginated list of the `{$this->toType}` items.";
            } else {
                return "Gives a paginated list of the connected `{$this->toType}` items of to this `{$this->fromType}`.";
            }
        }
        return $description;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ARGUMENT DEFINITIONS ------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function args()
    {
        $toType = GraphQL::type($this->toType);
        if(!($toType instanceof ModelTypeContract)) {
            throw new InvariantViolation("The toType has to point to a ModelTypeContract.");
        }

        $args = [

            // ARGUMENTS FOR THE PAGINATION
            'first' => [
                'type' => GraphQL::type('Int'),
                'description' => '**[Pagination]** The maximum amount of items you want to display on one page.',
                'default' => 15,
            ],
            'after' => [
                'type' => GraphQL::type('Cursor'),
                'description' => '**[Pagination]** The cursor to the position that should be the start of the page.',
            ],
            'offset' => [
                'type' => GraphQL::type('Int'),
                'description' => '**[Pagination]** The number of items in the list that should be skipped.',
                'default' => 0
            ],
            'page' => [
                'type' => GraphQL::type('Int'),
                'description' => '**[Pagination]** The number of the page that you want to display.',
                'default' => 1
            ],
        ];

        // ARGUMENTS FOR ORDERING
        if($toType->orderable()) {
            $args['orderBy'] = [
                'type' => $toType->getOrderByInputType(),
                'description' => '**[Ordering]** Specifies how the items should be ordered in the resulting list.'
            ];
        }


        if($toType->filterable()) {
            $args['filter'] = [
                'type' => $toType->getFilterInputType(),
                'description' => '**[Filtering]** Configure the filters on this list.',
            ];
        }

        if($toType->searchable()) {
            $args['search'] = [
                'type' => GraphQL::type('String'),
                'description' => '**[Searching]** Searches trough the list using the provided string.'
            ];
        }

        return $args;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RESOLVER ------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    public function resolver()
    {
        $resolver = array_get($this->config,'resolve');
        if($resolver === null) {
            if($this->fromQueryType()) {
                return new QueryModelListResolver();
            } else {
                return new ModelConnectionResolver();
            }
        }
        return $resolver;
    }

}