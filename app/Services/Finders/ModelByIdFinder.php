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
class ModelByIdFinder implements ModelFinder
{

    use UseModelClassAndNameProperties;

    /**
     * ModelByIdFinder constructor.
     * @param string $modelName
     * @param string $modelClass
     */
    public function __construct($modelName, $modelClass)
    {
        $this->modelName = $modelName;
        $this->modelClass = $modelClass;
    }

    /**
     * @inheritdoc
     */
    public function accepts($input): bool
    {
        if(is_subclass_of($input, $this->modelClass()) || is_a($input, $this->modelClass())) {
            return true;
        }

        if(is_integer($input)) {
            return true;
        }

        if(ctype_digit($input)) {
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

        if(ctype_digit($input)) {
            $model = $modelClass::find(intval($input));
        }

        if (is_subclass_of($model, $modelClass) || is_a($model, $modelClass)) {
            return $model;
        }

        throw new ModelNotFoundException;
    }

}