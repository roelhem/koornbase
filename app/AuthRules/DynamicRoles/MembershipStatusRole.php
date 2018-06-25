<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 09:14
 */

namespace App\AuthRules\DynamicRoles;


use App\Enums\MembershipStatus;
use App\Person;
use Roelhem\RbacGraph\Contracts\Authorizable;
use Roelhem\RbacGraph\Rules\DynamicRole;

class MembershipStatusRole extends DynamicRole
{

    protected $status = 0;

    protected $forAuthorizableTypes = [
        Person::class
    ];

    public function __construct($status)
    {
        $this->status = $status;
        $label = MembershipStatus::getLabel($this->status);
    }

    public function shouldAssignTo($authorizable)
    {
        if($authorizable instanceof Person) {
            return $authorizable->membership_status === $this->status;
        } else {
            return false;
        }
    }

    public function defaultNodeName()
    {
        return 'membership_status.'.MembershipStatus::getKey($this->status);
    }

    public function defaultNodeTitle()
    {
        $label = MembershipStatus::getLabel($this->status);

        return 'Lidmaatschap-status '.$label;
    }

    public function defaultNodeDescription()
    {
        $label = MembershipStatus::getLabel($this->status);

        return "Wordt automatisch toegekend aan gebruikers die gekoppeld zijn aan een persoon die als $label geregistreerd staan.";
    }

    public function constructorAttributes()
    {
        return [$this->status];
    }

}