<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 03:45
 */

namespace Roelhem\GraphQL\Http\Controllers;



use Illuminate\Routing\Controller;
use Psr\Http\Message\ServerRequestInterface;
use Roelhem\GraphQL\GraphQL as GraphQLHelper;
use Roelhem\GraphQL\GraphQL;

class GraphQLController extends Controller
{


    public function endpoint(ServerRequestInterface $request, GraphQLHelper $gql)
    {

        $response = $gql->server()->executePsrRequest($request)->toArray();

        return $response;

    }

}