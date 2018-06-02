<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 31-05-18
 * Time: 19:53
 */

namespace App\Services\Finders;


use App\Contracts\Finders\ModelFinder;
use App\Exceptions\Finders\InputNotAcceptedException;
use App\Exceptions\Finders\ModelNotFoundException;


/**
 * Class ModelByIdFinder
 *
 * Een abstract class voor ModelFinders die alleen models kunnen vinden via een instance van de model of via een
 * integer die de id van de Model bevat.
 *
 * @package App\Services\Finders
 */
abstract class ModelByIdFinder implements ModelFinder
{

    /**
     * @inheritdoc
     */
    public function accepts($input): bool
    {
        if(is_a($input, $this->modelClass())) {
            return true;
        }

        if(is_integer($input)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function find($input)
    {
        if(!$this->accepts($input)) {
            throw new InputNotAcceptedException;
        }

        $modelClass = $this->modelClass();

        if(is_a($input, $modelClass)) {
            return $input;
        }

        $model = null;

        if(is_integer($input)) {
            $model = $modelClass::find($input);
        }

        if (is_a($input, $modelClass)) {
            return $model;
        }

        throw new ModelNotFoundException;
    }

}