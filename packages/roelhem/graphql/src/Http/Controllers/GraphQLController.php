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
use Laravel\Passport\Passport;
use Psr\Http\Message\ServerRequestInterface;
use Roelhem\GraphQL\GraphQL as GraphQLHelper;
use Roelhem\GraphQL\GraphQL;
use Roelhem\GraphQL\Resolvers\DefaultResolver;
use Roelhem\GraphQL\Resolvers\MiddlewareResolver;
use Roelhem\GraphQL\Resolvers\ResolveContext;

class GraphQLController extends Controller
{


    public function endpoint(ServerRequestInterface $request, GraphQLHelper $gql)
    {

        $response = $gql->server()->executePsrRequest($request)->toArray();

        return $response;

    }

}