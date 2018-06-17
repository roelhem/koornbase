<?php

Rbac::superRole()->title('Super-user');


// ---------------------------------------------------------------------------------------------------------------- //
// -------  ADMINISTRATION ROLES  --------------------------------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------- //

Rbac::role('Admin')->title('Admin')->assign(
    Rbac::role('AdminDB')->title('Database-beheerder'),
    Rbac::role('AdminServer')->title('Server-beheerder'),
    Rbac::role('AdminApps')->title('Applicatiebeheerder'),
    Rbac::role('Webmaster')->title('Webmaster'),
    Rbac::role('Moderator')->title('Moderator')
);


// ---------------------------------------------------------------------------------------------------------------- //
// -------  DEVELOPMENT ROLES  ------------------------------------------------------------------------------------ //
// ---------------------------------------------------------------------------------------------------------------- //

Rbac::abstractRole('Dev')->title('Systeemontwikkeling')
    ->description('Voor alles dat op wat voor mannier dan ook te maken heeft met het verder ontwikkelen van het systeem')
    ->assignTo([

        Rbac::abstractRole('DevBackend')->title('Backend-Onwikkeling')->assignTo([
            Rbac::role('BackendDeveloper')->title('Backend Ontwikkelaar'),
            Rbac::role('DatabaseDeveloper')->title('Database Ontwikkelaar')
        ]),
        Rbac::abstractRole('DevFrontend')->title('Frontend-Ontwikkeling')->assignTo([
            Rbac::role('Designer')->title('Designer'),
            Rbac::role('FrontendDeveloper')->title('Frontend Onwikkelaar')
        ]),
        Rbac::abstractRole('DevApi')->title('API-Ontwikkeling')->assignTo([
            Rbac::role('ApiDeveloper')->title('API-Ontwikkelaar')
        ]),
        Rbac::abstractRole('DevTesting')->title('Testen van het systeem')->assignTo([
            Rbac::role('TestUser')->title('Test-gebruiker')
                ->description('Gebruiker die is aangemaakt voor testdoeleinden.'),
            Rbac::role('Tester')->title('Tester')
                ->description('Probeert zo veel mogelijk dingen uit op het systeem en raporteert als er onverwachte dingen gebeuren.'),
            Rbac::role('DevReviewer')->title('Waarnemer van onwikkelprocessen')
        ]),

        Rbac::role('MasterDeveloper')->title('Hoofd-ontwikkelaar')
            ->description('Overziet alle ontwikkelprocessen.')
            ->assign('DevBackend','DevFrontend','DevApi','DevTesting')
            ->assign('BackendDeveloper','DatabaseDeveloper','FrontendDeveloper','ApiDeveloper','Tester')

    ]);






// ---------------------------------------------------------------------------------------------------------------- //
// -------  DYNAMIC ROLES  ---------------------------------------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------- //


Rbac::dynamicRole('User')->title('Gebruiker')
    ->description('Rol die automatisch wordt toegekend aan iedere gebruiker die is ingelogd.');



Rbac::dynamicRole('Person')->title('Persoon')
    ->description('Rol die automatisch wordt toegekend aan iedere gebruiker die in de database is gekoppeld aan een persoon.');



Rbac::group('membership_status.', function () {

    Rbac::dynamicRole('Outsider')->title('Buitenstaander')
        ->description('Rol die automatisch wordt toegekend aan iedere gebruiker met een gekoppeld persoon die als Buitenstaander staat geregistreerd.');

    Rbac::dynamicRole('Novice')->title('Kennismaker')
        ->description('Rol die automatisch wordt toegekend aan iedere gebruiker met een gekoppeld persoon die als Kennismaker staat geregistreerd.');

    Rbac::dynamicRole('Member')->title('Lid')
        ->description('Rol die automatisch wordt toegekend aan iedere gebruiker met een gekoppeld persoon die als Lid staat geregistreerd.');

    Rbac::dynamicRole('FormerMember')->title('Oud-lid')
        ->description('Rol die automatisch wordt toegekend aan iedere gebruiker met een gekoppeld persoon die als Oud-lid staat geregistreerd.');

});


// ---------------------------------------------------------------------------------------------------------------- //
// -------  KOORNBEURS SPECIFIC ROLES  ---------------------------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------- //

Rbac::role('NoviceTutors')->title('Begeleiden van Kennismakers');