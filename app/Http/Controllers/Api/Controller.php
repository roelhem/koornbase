<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 21:49
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as ParentController;
use App\Http\Resources\Api\Resource;
use App\Services\Sorters\Sorter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class Controller
 *
 * A controller that has some additional functions to help controllers with parsing requests to the api.
 *
 * @package App\Http\Controllers\Api
 */
class Controller extends ParentController
{

    /**
     * @var string   The class name of the Model of this controller
     */
    protected $modelClass = Model::class;

    /**
     * @var string   The class name of the Resource of this controller
     */
    protected $resourceClass = Resource::class;

    /**
     * @var string    The class name of the Sorter of this controller
     */
    protected $sorterClass = Sorter::class;


    /**
     * The default index action. Shows a paginating list of all the models.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request) {
        $modelClass = $this->modelClass;
        $resourceClass = $this->resourceClass;
        $sorter = resolve($this->sorterClass);

        $query = $modelClass::query();
        $query = $sorter->addList($query, $this->getSortList($request));
        $query->with($this->getAskedRelations($request));

        $paginate = $query->paginate();

        return $resourceClass::collection($paginate);
    }

    /**
     * A function that prepares a model before it is send as a response of an action.
     *
     * @param $model
     * @param Request $request
     * @return Resource
     */
    protected function prepare($model, Request $request) {
        $resourceClass = $this->resourceClass;
        $model->load($this->getAskedRelations($request));
        return new $resourceClass($model);
    }


    /**
     * Returns an array of sorting-settings from the request that can be used in a sorter.
     *
     * @param Request $request
     * @return array|string
     */
    protected function getSortList(Request $request) {
        $sort = $request->query('sort', []);

        if(is_string($sort)) {
            $sort = explode(',', $sort);
        }

        if(!is_array($sort)) {
            return [];
        }

        return $sort;
    }

    /**
     * Returns an array of relations where the request asked for.
     *
     * @param Request $request
     * @return array
     */
    protected function getAskedRelations(Request $request) {
        $with = $request->query('with', []);

        if(is_string($with)) {
            $with = explode(',', $with);
        }

        if(!is_array($with)) {
            return [];
        }

        return $with;
    }

}