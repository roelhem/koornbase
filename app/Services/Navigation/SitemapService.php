<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 02-05-18
 * Time: 03:23
 */

namespace App\Services\Navigation;


class SitemapService extends NavigationService
{
    protected function init() {
        return [
            'root' => [
                'home' => [
                ],
                'people' => [
                    'people.index',
                ],
                'events' => [
                    'events.index',
                ],
                'auth-manager',
                'statistics',
                'crud' => [
                    'crud.persons',
                    'crud.studies',
                    'crud.budgets',
                    'crud.events',
                    'crud.groups',
                    'crud.group-categories',
                ],
                'settings',
                'me' => [
                    'login' => [
                        'password.request'
                    ],
                    'logout',
                ],
                'sitemap',
                'develop' => [
                    'develop.nav'
                ]
            ]
        ];
    }
}