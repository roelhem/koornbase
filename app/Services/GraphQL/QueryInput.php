<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 06:22
 */

namespace App\Services\GraphQL;


use App\Events\GraphQL\OperationExecuted;
use App\OAuth\Token;
use App\User;
use Illuminate\Support\Fluent;

/**
 * Class QueryInput
 * @package App\Services\GraphQL
 */
class QueryInput extends Fluent
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INITIALIZATION ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * QueryInput constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        if($this->get('schema') === null) {
            $this['schema'] = config('graphql.default_schema');
        }

        if($this->get('context') === null) {
            try {
                $this['context'] = app('auth')->user();
            } catch (\Exception $exception) {}
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GET PROPERTIES WITH DYNAMIC PROPERTY NAMES --------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the operation name of the operation in the query.
     *
     * @return string|null
     */
    public function getOperationName()
    {
        return $this->get(config('graphql.operation_name_key'));
    }

    /**
     * Returns the array of variables.
     *
     * @return array
     */
    public function getVariables()
    {
        $variables = $this->get(config('graphql.params_key'));
        if(is_string($variables)) {
            return json_decode($variables, true);
        }
        if(is_array($variables)) {
            return $variables;
        }
         return [];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RUN ------------------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    public function run()
    {
        $result = \GraphQL::query($this->get('query'), $this->getVariables(), [
            'schema' => $this->get('schema'),
            'context' => $this->get('context'),
            'operationName' => $this->getOperationName(),
        ]);

        $this->emitExecuted();

        return $result;
    }

    protected function emitExecuted()
    {
        event(new OperationExecuted($this->toStorableArray()));
    }

    public function toStorableArray()
    {
        return [
            'schema' => $this->get('schema'),
            'query' => $this->get('query'),
            'operationName' => $this->getOperationName(),
            'variables' => $this->getVariables(),
            'context' => $this->getContextArray(),
        ];
    }

    public function getContextArray()
    {
        $res = [
            'user_id' => null,
            'client_id' => null,
            'access_token_id' => null,
        ];
        $user = $this->get('context');
        if($user instanceof User) {
            $res['user_id'] = $user->id;
            $token = $user->token();
            if($token instanceof Token) {
                $res['client_id'] = $token->client_id;
                $res['access_token_id'] = $token->id;
            }
        }
        return $res;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- STATIC CONSTRUCTORS -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public static function createFromInput($input, $schema = null, $context = null)
    {
        $attributes = array_only($input, [
            'query',
            config('graphql.operation_name_key'),
            config('graphql.params_key')
        ]);

        if($schema !== null) {
            $attributes['schema'] = $schema;
        }

        if($context !== null) {
            $attributes['context'] = $context;
        }

        return new static($attributes);
    }

}