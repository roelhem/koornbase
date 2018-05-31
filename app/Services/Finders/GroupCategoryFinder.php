<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 31-05-18
 * Time: 08:44
 */

namespace App\Services\Finders;

use App\Contracts\Finders\ModelFinder;
use App\Exceptions\Finders\InputNotAcceptedException;
use App\Exceptions\Finders\ModelNotFoundException;
use App\GroupCategory;

/**
 * Class GroupCategoryFinder
 *
 * A ModelFinder that searches for a GroupCategory instance.
 *
 * @package App\Services\Finders
 */
class GroupCategoryFinder implements ModelFinder
{

    /**
     * @inheritdoc
     */
    public function modelClass(): string
    {
        return GroupCategory::class;
    }

    /**
     * @inheritdoc
     */
    public function accepts($input): bool
    {
        if($input instanceof GroupCategory) {
            return true;
        }

        if(is_integer($input)) {
            return true;
        }

        if(is_string($input)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     * @return GroupCategory
     */
    public function find($input)
    {
        if (!$this->accepts($input)) {
            throw new InputNotAcceptedException;
        }

        if($input instanceof GroupCategory) {
            return $input;
        }

        $model = null;

        if(is_integer($input)) {
            $model = GroupCategory::find($input);
        } else if(is_string($input)) {
            $model = GroupCategory::findBySlug($input);
        }

        if($model instanceof GroupCategory) {
            return $model;
        } else {
            throw new ModelNotFoundException;
        }
    }
}