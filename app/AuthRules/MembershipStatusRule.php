<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 16:33
 */

namespace App\AuthRules;


use App\Certificate;
use App\Contracts\OwnedByPerson;
use App\Debtor;
use App\Enums\MembershipStatus;
use App\KoornbeursCard;
use App\Membership;
use App\Person;
use App\PersonAddress;
use App\PersonEmailAddress;
use App\PersonPhoneNumber;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Roelhem\RbacGraph\Contracts\Rules\QueryRule;
use Roelhem\RbacGraph\Contracts\Rules\RuleAttributeBag;
use Roelhem\RbacGraph\Rules\BaseRule;

class MembershipStatusRule extends BaseRule implements QueryRule
{

    /**
     * @var MembershipStatus
     */
    protected $membershipStatus;

    /**
     * MembershipStatusRule constructor.
     *
     * @param MembershipStatus|integer $membershipStatus
     */
    public function __construct($membershipStatus)
    {
        $this->membershipStatus = MembershipStatus::get($membershipStatus);
    }

    /** @inheritdoc */
    public function constructorArguments()
    {
        return [$this->membershipStatus->getValue()];
    }

    /** @inheritdoc */
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

    /**
     * Returns true if the gate can be traversed, returns false otherwise.
     *
     * @param RuleAttributeBag $attributeBag
     * @return boolean
     */
    public function allows($attributeBag)
    {
        $person = null;

        $model = $attributeBag->model;
        if($model instanceof OwnedByPerson) {
            $person = $model->getOwner();
        }

        if($person === null) {
            return false;
        }

        return $person->membership_status->is($this->membershipStatus);
    }

    /**
     * Adds where-clauses to the provided query such that only models that conform to this rule will be shown.
     *
     * @param Builder $query
     * @param RuleAttributeBag $bag
     * @return Builder
     */
    public function queryFilter($query, $bag)
    {
        return $query->whereHas('owner', function(Builder $query) {
            return $query->membershipStatus($this->membershipStatus->getValue());
        });
    }
}