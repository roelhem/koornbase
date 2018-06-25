<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 09:05
 */

namespace App\AuthRules\DynamicRoles;


use App\Person;
use Roelhem\RbacGraph\Rules\DynamicRole;

class PersonRole extends DynamicRole
{

    protected $defaultNodeName = 'Person';
    protected $defaultNodeTitle = 'Als Persoon geregistreerd';
    protected $defaultNodeDescription = 'Een role voor alle gebruikers die gekoppeld zijn aan een persoon.';

    protected $forAuthorizableTypes = [
        Person::class
    ];

    public function shouldAssignTo($authorizable)
    {
        if($authorizable instanceof Person) {
            return true;
        } else {
            return false;
        }
    }

}