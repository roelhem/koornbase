<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 00:41
 */

namespace App\Services\GraphQL;
use App\User;


/**
 * Class Operation
 *
 * An operation for the GraphQL API
 *
 * @package App\Services\GraphQL
 */
class Operation
{

    /**
     * The reference to the GraphQL object
     *
     * @var \Rebing\GraphQL\GraphQL
     */
    protected $graphql;

    /**
     * The query-string that contains the operation.
     *
     * @var string
     */
    protected $query;

    /**
     * The name of the operation, if more than one operation is defined in the provided query-string.
     *
     * @var string|null
     */
    protected $operationName;

    /**
     * The name of the GraphQL-schema on which this operation should run.
     *
     * @var string
     */
    protected $schemaName;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INITIALISATION ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Operation constructor.
     * @param $query
     * @param null $operationName
     * @param null $schemaName
     */
    public function __construct($query, $operationName = null, $schemaName = null)
    {
        $this->graphql = app('graphql');

        $this->query = $query;
        $this->operationName = $operationName;
        if($schemaName !== null) {
            $this->schemaName = $schemaName;
        } else {
            $this->schemaName = self::getDefaultSchemaName();
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RUN ------------------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Runs this operation using the provided variables and returns the result.
     *
     * @param array|string|null $variables
     * @param User|null $context
     * @return mixed
     */
    public function run($variables = null, $context = null)
    {
        return $this->graphql->query(
            $this->getQueryString(),
            $this->parseVariables($variables),
            $this->getOpts($context)
        );
    }

    /**
     * Parses the input variables for the run request.
     *
     * @param array|string|null $variables
     * @return array|mixed|null
     */
    protected function parseVariables($variables = null)
    {
        if(is_string($variables)) {
            return json_decode($variables, true);
        }

        if(is_array($variables)) {
            return $variables;
        }

        return [];
    }

    /**
     * The options for the GraphQL::query() method.
     *
     * @param User|null $context
     * @return array
     */
    protected function getOpts($context = null)
    {
        // The default options
        $res = [
            'schema' => $this->getSchemaName(),
        ];

        // The context.
        if($context instanceof User) {
            $res['context'] = $context;
        } else {
            $defaultContext = self::getDefaultContext();
            if($defaultContext instanceof User) {
                $res['context'] = $defaultContext;
            }
        }

        // The operation-name.
        if($this->operationName !== null) {
            $res['operationName'] = $this->operationName;
        }

        return $res;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTERS -------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Returns the name of the schema on which this
     *
     * @return string
     */
    public function getSchemaName()
    {
        return $this->schemaName;
    }

    /**
     * Returns the GraphQL-query string in which this operation is defined.
     *
     * @return string
     */
    public function getQueryString()
    {
        return $this->query;
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- STATIC: DEFAULT VALUES ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the name of the default schema.
     *
     * @return string
     */
    protected static function getDefaultSchemaName()
    {
        return config('graphql.default_schema');
    }

    /**
     * Returns the default user-context an operation.
     *
     * @return User|null
     */
    protected static function getDefaultContext()
    {
        return \Auth::user();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- STATIC: CONSTRUCTORS ------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Creates a new operation from a GraphQL-request input-array.
     *
     * @param array $array
     * @param null $schemaName
     * @return static
     */
    public static function fromInputArray($array, $schemaName = null)
    {
        return new Operation(
            array_get($array,'query'),
            array_get($array, config('graphql.operation_name_key')),
            $schemaName
        );
    }


}