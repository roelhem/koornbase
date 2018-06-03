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

abstract class ModelByIdOrSlugFinder extends ModelByIdFinder
{

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
        if (!$this->accepts($input)) {
            throw new InputNotAcceptedException;
        }

        $modelClass = $this->modelClass();

        if(is_a($input, $modelClass)) {
            return $input;
        }

        $model = null;

        if(is_integer($input)) {
            $model = $modelClass::find($input);
        } else if(is_string($input)) {
            $model = $modelClass::findBySlug($input);
        }

        if(is_subclass_of($model, $modelClass) || is_a($model, $modelClass)) {
            return $model;
        } else {
            throw new ModelNotFoundException;
        }
    }
}