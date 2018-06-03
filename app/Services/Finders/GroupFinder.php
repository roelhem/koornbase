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
class GroupFinder extends ModelByIdOrSlugFinder
{

    /**
     * @inheritdoc
     */
    public function modelClass(): string
    {
        return Group::class;
    }

}