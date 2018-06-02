<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 31-05-18
 * Time: 19:52
 */

namespace App\Services\Finders;

use App\Person;

/**
 * Class PersonFinder
 *
 * A ModelFinder that
 *
 * @package App\Services\Finders
 */
class PersonFinder extends ModelByIdFinder
{

    public function modelClass(): string
    {
        return Person::class;
    }

}