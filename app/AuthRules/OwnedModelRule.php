<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 04:30
 */

namespace App\AuthRules;


use App\Certificate;
use App\Contracts\OwnedByPerson;
use App\Debtor;
use App\KoornbeursCard;
use App\Membership;
use App\Person;
use App\PersonAddress;
use App\PersonEmailAddress;
use App\PersonPhoneNumber;
use App\User;
use Roelhem\RbacGraph\Contracts\Rules\ModelRule;
use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag;
use Roelhem\RbacGraph\Rules\BaseRule;

class OwnedModelRule extends BaseRule implements ModelRule
{

    /**
     * Returns true if the gate can be traversed, returns false otherwise.
     *
     * @param RuleAttributeBag $attributeBag
     * @return boolean
     */
    public function allows($attributeBag)
    {
        // collect the right authorizables
        $authorizable = $attributeBag->authorizable;
        $person = null;

        if($authorizable instanceof User) {
            $person = $authorizable->person;
        } elseif($authorizable instanceof Person) {
            $person = $authorizable;
        }

        if($person === null) {
            return null;
        }

        $model = $attributeBag->model;
        if($model instanceof OwnedByPerson) {
            return $model->getOwnerId() === $person->getId();
        }

        return false;
    }

    /**
     * @return array
     */
    public function for()
    {
        return [
            User::class,
            Person::class,
            PersonAddress::class,
            PersonEmailAddress::class,
            PersonPhoneNumber::class,
            KoornbeursCard::class,
            Membership::class,
            Debtor::class,
            Certificate::class
        ];
    }
}