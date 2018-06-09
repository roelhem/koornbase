<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 18:00
 */

use App\Facades\Rbac;


// ROLES FOR THE LOGIN STATUS
Rbac::role('guest','Guest','Role voor als er geen gebruiker is ingelogd.')->required();

Rbac::role('user','Gebruiker','Role voor iedere gebruiker die is ingelogd.')->required();


// SPECIAL ROLES
Rbac::role('super','Super','Role die alle beveiligingen omzeilt.')->required();

Rbac::role('admin','Admin','Role die alle permissions heeft.')->required();



// ROLES DEPENDING ON THE STATUS OF THE CONNECTED PERSON
Rbac::role('person','Persoon','Gebruikers die gekoppeld zijn aan een persoon.')->required();


// By membership state

Rbac::role(
    'membership_status',
    'Lidstatus parent-role.',
    'Een role die de parent is van alle lidstatus-rollen. Deze rol is voor testdoeleinden en voor ondersteuning van gebruikers met een andere lidstatus.'
);

foreach (\App\Enums\MembershipStatus::getValues() as $value) {
    Rbac::role(
        \App\Enums\MembershipStatus::getRoleId($value),
        \App\Enums\MembershipStatus::getLabel($value),
        "Automatisch toegekend aan gebruikers met personen die voor hun huidige lidstatus als '".
        \App\Enums\MembershipStatus::getLabel($value).
        "' geregistreerd staan. Als de lidstatus van de persoon veranderd, verliest hij of zij automatisch deze rol."
    )->assignToRole('membership_status');
}