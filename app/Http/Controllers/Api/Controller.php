<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 21:49
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as ParentController;
use App\Http\Middleware\Api\PrepareModelResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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