<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 18:56
 */

namespace App\AuthRules\DynamicRoles;


use App\User;
use Roelhem\RbacGraph\Rules\DynamicRole;

class ActiveUserRole extends DynamicRole
{

    protected $defaultNodeName = 'ActiveUser';
    protected $defaultNodeTitle = 'Actieve Gebruiker';
    protected $defaultNodeDescription = 'Een gebruiker met een account die normaal gebruikt kan worden.
        Wordt automatisch toegekend.';

    protected $forAuthorizableTypes = [
        User::class
    ];

    public function shouldAssignTo($authorizable)
    {
        return $authorizable instanceof User;
    }

}