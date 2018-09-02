<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 00:26
 */

namespace App\Http\Controllers;

use App\Services\GraphQL\Operation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Rebing\GraphQL\GraphQLUploadMiddleware;

/**
 * Class GraphQLController
 *
 * The controller that handles all the requests to the GraphQL endpoint.
 *
 * @package App\Http\Controllers
 */
class GraphQLController extends Controller
{

    /**
     * Action that handles the incomming GraphQL-queries.
     *
     * @param Request $request
     * @return array
     */
    public function query(Request $request)
    {
        $request = $this->processUploadRequest($request);
        if($this->isBatch($request)) {
            return $this->getBatch($request)->map(function($batchItem) use ($request) {
                return $this->runInputArray($batchItem, $request);
            })->all();
        } else {
            return $this->runInputArray($request->all(), $request);
        }
    }

    /**
     * Applies the GraphQLUploadMiddleware.
     *
     * @param Request $request
     * @return Request
     */
    protected function processUploadRequest($request)
    {
        $middleware = new GraphQLUploadMiddleware();
        return $middleware->processRequest($request);
    }

    /**
     * Runs the array of a GraphQL-request input.
     *
     * @param array $inputArray
     * @param Request|null $request
     * @return mixed
     */
    protected function runInputArray($inputArray, $request = null)
    {
        $paramKey = config('graphql.params_key');
        $operation = Operation::fromInputArray($inputArray, $this->getSchemaName($request));
        return $operation->run(array_get($inputArray, $paramKey));
    }

    /**
     * Returns the name of the Schema that should be used according to the current request
     *
     * @param Request $request
     * @return string
     */
    protected function getSchemaName($request)
    {
        if(count($request->route()->parameters) > 1) {
            return implode('/', $request->route()->parameters);
        }
        return config('graphql.default_schema');
    }

    /**
     * Returns whether or not the the input should be read as an batch input.
     *
     * @param Request $request
     * @return bool
     */
    protected function isBatch($request)
    {
        return !$request->has('query');
    }

    /**
     * Returns an array with all the operations in the query.
     *
     * @param Request $request
     * @return Collection
     */
    protected function getBatch($request)
    {
        if($this->isBatch($request)) {
            return collect($request->all());
        } else {
            return collect([$request->all()]);
        }
    }

}