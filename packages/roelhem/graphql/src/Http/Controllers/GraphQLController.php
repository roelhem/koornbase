<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 03:45
 */

namespace Roelhem\GraphQL\Http\Controllers;


use App\Http\GraphQLNew\Query;
use GraphQL\Error\Debug;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Schema;
use Illuminate\Routing\Controller;
use Psr\Http\Message\ServerRequestInterface;
use Roelhem\GraphQL\GraphQL as GraphQLHelper;
use Roelhem\GraphQL\GraphQL;
use Roelhem\GraphQL\Resolvers\DefaultResolver;
use Roelhem\GraphQL\Resolvers\MiddlewareResolver;

class GraphQLController extends Controller
{


    public function endpoint(ServerRequestInterface $request, GraphQLHelper $gql)
    {

        $tl = $gql->typeLoader();

        $schema = new Schema([
            'query' => $gql->type('Query'),
            'typeLoader' => $tl,
            'types' => $gql->types(),
        ]);



        $server = new StandardServer([
            'schema' => $schema,
            'queryBatching' => true,
            'debug' => Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE,
            'fieldResolver' => new DefaultResolver(),
        ]);

        return $server->executePsrRequest($request)->toArray();
    }

}