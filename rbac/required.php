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
    'membership_status.outsider',
    'Buitenstaander',
    'Gebruikers van personen die als buitenstaander staan geregistreerd.'
)->required();

Rbac::role(
    'membership_status.novice',
    'Kennismaker',
    'Gebruikers van personen die als kennismaker staan geregistreerd.'
)->required();

Rbac::role(
    'membership_status.member',
    'Lid',
    'Gebruikers van personen die als lid staan geregistreerd.'
)->required();

Rbac::role(
    'membership_status.former_member',
    'Oud-lid',
    'Gebruikers van personen die als oud-lid staan geregistreerd.'
)->required();