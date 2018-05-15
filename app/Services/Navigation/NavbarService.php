<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 01-05-18
 * Time: 13:58
 */

namespace App\Services\Navigation;


class NavbarService extends NavigationService
{

    protected function init() {
        return [
            'home',
            'people' => [
                'people.index',
            ],
            'events' => [
                'events.index',
            ],
            'auth-manager',
            'statistics',
            'crud' => [
                'crud.persons' => [
                    'crud.persons.index',
                    'crud.persons.create',
                ],
                'crud.studies' => [
                    'crud.studies.index',
                    'crud.studies.create',
                ],
                'crud.budgets' => [
                    'crud.budgets.index',
                    'crud.budgets.create',
                ],
                'crud.events' => [
                    'crud.events.index',
                    'crud.events.create'
                ],
                'crud.groups' => [
                    'crud.groups.index',
                    'crud.groups.create'
                ],
                'crud.group-categories' => [
                    'crud.group-categories.index',
                    'crud.group-categories.create'
                ],
            ],
            'settings'
        ];
    }

}