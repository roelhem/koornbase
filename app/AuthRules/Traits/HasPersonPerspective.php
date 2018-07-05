<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 16:36
 */

namespace App\AuthRules\Traits;


use App\Person;
use App\User;
use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag;

trait HasPersonPerspective
{

    /**
     * Returns the id of the subject person
     *
     * @param RuleAttributeBag $bag
     * @return integer|null
     */
    protected function getPersonId($bag) {
        $authorizable = $bag->authorizable;

        if($authorizable instanceof User) {
            return $authorizable->person_id;
        } elseif($authorizable instanceof Person) {
            return $authorizable->id;
        }

        return null;
    }

    /**
     * Returns the id of the subject person
     *
     * @param RuleAttributeBag $bag
     * @return Person|null
     */
    protected function getPerson($bag) {
        $authorizable = $bag->authorizable;

        if($authorizable instanceof User) {
            return $authorizable->person;
        } elseif($authorizable instanceof Person) {
            return $authorizable;
        }

        return null;
    }

}