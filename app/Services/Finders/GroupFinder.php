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
use App\Group;

/**
 * Class GroupFinder
 *
 * A ModelFinder that searches for a Group instance.
 *
 * @package App\Services\Finders
 */
class GroupFinder implements ModelFinder
{

    /**
     * @inheritdoc
     */
    public function modelClass(): string
    {
        return Group::class;
    }

    /**
     * @inheritdoc
     */
    public function accepts($input): bool
    {
        if($input instanceof Group) {
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
     * @return Group
     */
    public function find($input)
    {
        if (!$this->accepts($input)) {
            throw new InputNotAcceptedException;
        }

        if($input instanceof Group) {
            return $input;
        }

        $model = null;

        if(is_integer($input)) {
            $model = Group::find($input);
        } else if(is_string($input)) {
            $model = Group::findBySlug($input);
        }

        if($model instanceof Group) {
            return $model;
        } else {
            throw new ModelNotFoundException;
        }
    }
}