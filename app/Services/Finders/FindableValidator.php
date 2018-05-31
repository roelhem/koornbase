<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 31-05-18
 * Time: 09:18
 */

namespace App\Services\Finders;
use App\Contracts\Finders\FinderCollection;
use App\Exceptions\Finders\InputNotAcceptedException;
use App\Exceptions\Finders\ModelNotFoundException;

/**
 * Class FindableValidator
 *
 * A class that defines functions to check if a class can be found by the provided input.
 *
 * @package App\Services\Finders
 */
class FindableValidator
{

    protected $finderCollection;

    public function __construct(FinderCollection $finderCollection)
    {
        $this->finderCollection = $finderCollection;
    }


    public function validatedFindable($attribute, $value, $parameters) {
        $modelClass = $parameters[0];
        if($this->finderCollection->canFind($modelClass)) {
            try {
                $model = $this->finderCollection->find($value, $modelClass);
                if (is_a($model, $modelClass)) {
                    return true;
                } else {
                    return false;
                }
            } catch (ModelNotFoundException $e) {
                return false;
            } catch (InputNotAcceptedException $e) {
                return false;
            }
        }
        return false;
    }

}