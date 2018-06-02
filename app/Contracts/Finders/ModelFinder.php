<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 31-05-18
 * Time: 08:33
 */

namespace App\Contracts\Finders;

use App\Exceptions\Finders\InputNotAcceptedException;
use App\Exceptions\Finders\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface ModelFinder
 *
 * This contract gives the structure to create ModelFinders. These services try to find an instance of a model
 * based on different input values that can represent the model.
 *
 * @package App\Contracts\Finders
 */
interface ModelFinder
{

    /**
     * Returns the class of the model that is searched by this ModelFinders.
     *
     * @return string
     */
    public function modelClass() : string;

    /**
     * Returns if the ModelFinder accepts the type of the given input.
     *
     * @param mixed input
     * @return bool
     */
    public function accepts($input) : bool;

    /**
     * Tries to find the Model based on the input.
     *
     * @param $input
     * @return Model
     * @throws ModelNotFoundException
     * @throws InputNotAcceptedException
     */
    public function find($input);

}