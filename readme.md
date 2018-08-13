## Over de KoornBase

De KoornBase is de centrale database van [O.J.V. de Koornbeurs](http://www.koornbeurs.nl/). Het systeem is hoofdzakelijk gemaakt met [Laravel](https://laravel.com/).


## Start de test-server.

Zorg ten eerste dat je [Composer](https://getcomposer.org) en de [Homestead Vagrant Box](https://laravel.com/docs/5.6/homestead#installation-and-setup) hebt geïnstalleerd. Voer daarna de volgende stappen uit:

1. Clone deze repository
2. Voer `composer update` uit in de map van het project.
3. Voer `vagrant up` uit in de map van het project.

Elke stap kan behoorlijk lang duren. Voer `vagrant ssh` uit om de server te initialiseren. Op de server, doe dan het volgende:

1. voer `cd code` uit om naar de map waarin de broncode staat te gaan.
2. voer `php artisan migrate:fresh --seed` uit om de database te initialiseren en te vullen met willekeurig gegegenereerde data.

Nadat alles klaar is, kun je de website openen via `https://127.0.0.1:43300`. 

Je kunt inloggen met het account `super@koornbeurs.nl` en wachtwoord `secret`. Dit account heeft altijd alle rechten in de database.

## Over het project

Dit project is in *april 2018* gestart door Roel Hemerik in opdracht van de **Senaat** *(het adviesgevende orgaan van de Koornbeurs)* en de **GeeKi** *(De commissie die de computers in het gebouw van O.J.V. de Koornbeurs beheert)*.