<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 17:05
 */





Rbac::role('moderator')->name('Moderator')
    ->description('Corrigeert verkeerd gebruik van de database.');




// DEVELOPING
Rbac::role('developer')->name('Ontwikkelaar')
    ->description('Ontwikkelaar aan het interne gedeelte van het KoornBase-systeem.');

// BACK-END

Rbac::role('backend-developer','Backend Ontwikkelaar',
    'Ontwikkelaars die zich bezig houden met het achterliggende centrale systeem.')
    ->assignToRole('developer');

Rbac::role('feature-test-runner', 'Feature-test runner',
    'Speciale selectie van ontwikkelaars die Feature-tests op de server mogen uitvoeren.')
    ->assignRole('backend-developer');


// FRONT-END
Rbac::role('frontend-developer','Frontend Ontwikkelaar')
    ->description('Ontwikkelaars die zich met het gebruikersgedeelde van het KoornBase-systeem bezig houdt.')
    ->assignToRole('developer');

Rbac::role('designer','Designer')
    ->description('Houdt zich bezig met het uiterlijk van de frontend.')
    ->assignToRole('frontend-developer');


// REVIEW AND TESTING
Rbac::role('code-reviewer','Code-Reviewer')
    ->description('Ontwikkelaars die de code van andere ontwikkelaars lezen en bekritiseren.')
    ->assignToRole('developer');

Rbac::role('tester','Testers')
    ->description('Gebruikers die de nieuwe functies van het KoornBase-systeem uittesten.')
    ->assignToRole('developer')
    ->assignToRole('code-reviewer')
    ->assignRole('membership_status');


// API
Rbac::role('api-developer','Api-Ontwikkelaar')
    ->description('Ontwikkelaars zich bezig houden met het ontwikkelen van de REST-API.')
    ->assignToRole('developer');

Rbac::role('client-developer', 'Client-Ontwikkelaar')
    ->description('Ontwikkelaars die toepassingen maken met de REST-API.')
    ->assignToRole('api-developer');

Rbac::role('app-manager', 'App-Beheerder')
    ->description('Een account die een bepaalde applicatie beheert die werkt met de REST-API.')
    ->assignToRole('api-developer')
    ->assignRole('client-developer');

Rbac::role('website-developer', 'Website-Ontwikkelaar')
    ->description('Een ontwikkelaar die zich bezig houdt met de bezoekerswebsite van de Koornbeurs.')
    ->assignToRole('api-developer')
    ->assignRoles(['client-developer','app-manager']);


Rbac::role('api-student','API Student')
    ->description('Voor accounts die willen experimenteren met de KoornBase-API.')
    ->assignToRole('client-developer');



Rbac::role('api-client-moderator','API-Client Moderator')
    ->description('Persoon die in de gaten houdt of er geen misbruik van de REST-API wordt gemaakt.')
    ->assignToRole('api-developer');




Rbac::role('commissielid','Commissielid')
    ->description('Is lid van een commissie van de Koornbeurs.');

