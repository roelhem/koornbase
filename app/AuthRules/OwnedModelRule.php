<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 04:30
 */

namespace App\AuthRules;


use App\AuthRules\Traits\HasPersonPerspective;
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
use Roelhem\RbacGraph\Contracts\Rules\QueryRule;
use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag;
use Roelhem\RbacGraph\Rules\BaseRule;

class OwnedModelRule extends BaseRule implements QueryRule
{

    use HasPersonPerspective;

    /**
     * Returns true if the gate can be traversed, returns false otherwise.
     *
     * @param RuleAttributeBag $attributeBag
     * @return boolean
     */
    public function allows($attributeBag)
    {
        $personId = $this->getPersonId($attributeBag);

        if($personId === null) {
            return false;
        }

        $model = $attributeBag->model;
        if($model instanceof OwnedByPerson) {
            return $model->getOwnerId() === $personId;
        }

        return false;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param RuleAttributeBag $bag
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function queryFilter($query, $bag)
    {
        $personId = $this->getPersonId($bag);
        if($personId !== null) {
            return $query->ownedBy($personId);
        } else {
            return $query->whereRaw('FALSE');
        }
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