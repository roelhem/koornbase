<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 02:08
 */

namespace App\Listeners\GraphQL;


use App\Events\GraphQL\OperationExecuted;
use App\Logs\LogGraphQLOperation;
use GraphQL\Language\AST\OperationDefinitionNode;
use GraphQL\Language\Parser;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class LogOperationExecuted
 *
 * Listens for executed operations an logs those operations.
 *
 * @package App\Listeners\GraphQL
 */
class LogOperationExecuted implements ShouldQueue
{


    public function __construct()
    {
    }

    public function handle(OperationExecuted $event)
    {
        $data = $event->data;

        $variables = array_get($data,'variables', []);
        if($variables === []) {
            $variables = new \stdClass();
        }

        $query = array_get($data, 'query');
        $operation_name = array_get($data, 'operationName');
        /** @var OperationDefinitionNode|null $def */
        $def = null;

        // Search the docs for extra values
        $docs = Parser::parse($query, ['noLocation' => true]);
        foreach ($docs->definitions as $definition) {
            if($definition instanceof OperationDefinitionNode) {
                $name = $definition->name ? $definition->name->value : null;
                if($operation_name === null) {
                    $def = $definition;
                    $operation_name = $name;
                    break;
                } elseif($operation_name === $name) {
                    $def = $definition;
                    break;
                }
            }
        }


        LogGraphQLOperation::create([
            'schema'          => array_get($data,'schema'),
            'operation_name'  => $operation_name,
            'query'           => $query,
            'type'            => $def->operation,
            'variables'       => $variables,
            'user_id'         => array_get($data,'context.user_id'),
            'client_id'       => array_get($data,'context.client_id'),
            'access_token_id' => array_get($data,'context.access_token_id'),
            'requested_at'    => $event->emitDate,
        ]);
    }

}