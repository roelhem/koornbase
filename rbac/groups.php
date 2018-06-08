<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 07-06-18
 * Time: 17:05
 */


use \App\Facades\Rbac;


Rbac::role('moderator')->name('Moderator')
    ->description('Corrigeert verkeerd gebruik van de database.');

Rbac::role('developer')->name('Ontwikkelaar')
    ->description('Ontwikkelaar aan het interne gedeelte van het KoornBase-systeem');

Rbac::role('commissielid')->name('Commissielid')
    ->description('Is lid van een commissie van de Koornbeurs.');
