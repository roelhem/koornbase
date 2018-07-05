<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-06-18
 * Time: 15:18
 */

namespace App\Services\Finders;


use App\Exceptions\Finders\InputNotAcceptedException;
use App\Exceptions\Finders\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;

class ModelByIdOrSlugFinder extends ModelByIdFinder
{

    /**
     * ModelByIdOrSlugFinder constructor.
     * @param string $modelName
     * @param string $modelClass
     */
    public function __construct(string $modelName, string $modelClass)
    {
        parent::__construct($modelName, $modelClass);
    }

    /**
     * @inheritdoc
     */
    public function accepts($input): bool
    {
        if(parent::accepts($input)) {
            return true;
        }

        if(is_string($input)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function find($input)
    {
        if (parent::accepts($input)) {
            return parent::find($input);
        }

        if (!$this->accepts($input)) {

            if(is_object($input)) {
                $type = get_class($input);
            } else {
                $type = gettype($input);
            }
            throw new InputNotAcceptedException("Can't accept an input of type $type.");
        }

        $modelClass = $this->modelClass();

        $model = null;

        if(is_string($input)) {
            $model = $modelClass::findBySlug($input);
        }

        if(is_subclass_of($model, $modelClass) || is_a($model, $modelClass)) {
            return $model;
        } else {
            throw new ModelNotFoundException;
        }

    }
}