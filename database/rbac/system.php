<?php

use \App\Role;

// THE ADMIN ROLE

Role::create([
    'id' => 'admin',
    'name' => 'Administrator',
    'description' => 'Heeft altijd alle rechten op de website.',
    'is_required' => true,
]);

Role::create([
    'id' => 'moderator',
    'name' => 'Moderator',
    'description' => 'Heeft de rechten om fouten (en ander slecht gebruik van het systeem) te hersellen.'
])->assignTo('admin');

Role::create([
    'id' => 'developer',
    'name' => 'Ontwikkelaar'
])->assignTo('admin');

// THE ROLES FOR THE LOGIN STATUS

Role::create([
    'id' => 'guest',
    'name' => 'Gast',
    'description' => 'Voor de gebruiker die niet is ingelogd.',
    'is_required' => true,
]);

Role::create([
    'id' => 'user',
    'name' => 'Gebruiker',
    'description' => 'Voor de gebruiker die is ingelogd.',
    'is_required' => true,
]);

// THE ROLES FOR THE MEMBERSHIP STATUS

Role::create([
    'id' => 'membershipStatus:Outsider',
    'name' => 'Buitenstaander',
    'description' => 'Voor de personen die geen lid zijn (en ook nooit lid zijn geweest.)',
    'is_required' => true,
]);

Role::create([
    'id' => 'membershipStatus:Novice',
    'name' => 'Kennismaker',
    'description' => 'Voor de personen met lidstatus "Kennismaker".',
    'is_required' => true,
]);

Role::create([
    'id' => 'membershipStatus:Member',
    'name' => 'Lid',
    'description' => 'Voor de personen met lidstatus "Lid". (Alle huidige leden van de Koornbeurs).',
    'is_required' => true,
]);

Role::create([
    'id' => 'membershipStatus:FormerMember',
    'name' => 'Oud-lid',
    'description' => 'Voor de personen met lidstatus "Oud-lid". (Alle personen die ooit lid zijn geweest van de Koornbeurs).',
    'is_required' => true,
]);