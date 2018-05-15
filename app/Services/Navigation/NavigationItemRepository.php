<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 01-05-18
 * Time: 22:11
 */

namespace App\Services\Navigation;

use App\Exceptions\NavigationItemNotFoundException;
use App\Services\Navigation\NavigationItem;
use Illuminate\Http\Request;

/**
 * Class NodeRepository
 *
 * Defines Navigation Nodes and provides them.
 *
 * @package App\Services\Navigation
 */
class NavigationItemRepository
{
    /**
     * @var array The items
     */
    protected $items = [
        'root' => [
            'name' => 'Admin website',
            'link' => '/',
            'icons' => [
                'fe' => 'globe',
                'fa' => 'globe'
            ]
        ],
        'sitemap' => [
            'name' => 'Sitemap',
            'link' => 'sitemap',
            'icons' => [
                'fa' => 'sitemap'
            ]
        ],
        'home' => [
            'name' => 'Home',
            'route' => 'home',
            'icons' => [
                'fe' => 'home',
                'fa' => 'home'
            ]
        ],
        'login' => [
            'name' => 'Inloggen',
            'route' => 'login',
            'icons' => [
                'fe' => 'log-in',
                'fa' => 'sign-in',
            ]
        ],
        'logout' => [
            'name' => 'Uitloggen',
            'route' => 'logout',
            'icons' => [
                'fe' => 'log-out',
                'fa' => 'sign-out',
            ]
        ],
        'password.request' => [
            'name' => 'Wachtwoord herstellen',
            'label' => 'Wachtwoord Vergeten?',
            'route' => 'password.request'
        ],
        'me' => [
            'name' => 'Mijn Account',
            'label' => 'Mijn Account',
            'route' => 'me',
            'icons' => [
                'fe' => 'user',
                'fa' => 'user'
            ]
        ],
        'people' => [
            'name' => 'Ledenbestand',
            'label' => 'Ledenbestand',
            'route' => 'people',
            'icons' => [
                'fe' => 'feather',
                'fa' => 'feather'
            ],
        ],
        'people.index' => [
            'name' => 'Personen Zoeken',
            'label' => 'Zoeken',
            'route' => 'people.index',
            'icons' => [
                'fe' => 'search',
                'fa' => 'search'
            ]
        ],
        'events' => [
            'name' => 'Evenementenbeheer',
            'label' => 'Evenementen',
            'icons' => [
                'fe' => 'calendar',
                'fa' => 'calendar'
            ],
        ],
        'events.index' => [
            'name' => 'Evenementen Zoeken',
            'label' => 'Zoeken',
            'route' => 'events.index',
            'icons' => [
                'fe' => 'search',
                'fa' => 'search'
            ]
        ],
        'auth-manager' => [
            'name' => 'Gebruikersbeheer',
            'label' => 'Gebruikers',
            'icons' => [
                'fe' => 'users',
                'fa' => 'users'
            ],
        ],
        'statistics' => [
            'name' => 'Statestieken',
            'icons' => [
                'fa' => 'bar-chart-o',
                'fe' => 'bar-chart-2',
            ],
        ],
        'crud' => [
            'name' => 'Database',
            'icons' => [
                'fe' => 'database',
                'fa' => 'database'
            ],
        ],
        'settings' => [
            'name' => 'Instellingen',
            'route' => 'settings',
            'icons' => [
                'fe' => 'settings',
            ],
        ],
        'develop' => [
            'name' => 'Tools voor ontwikkelaars',
            'label' => 'Tools',
            'route' => 'develop',
            'icons' => [
                'fa' => 'wrench'
            ]
        ],
        'develop.nav' => [
            'name' => 'Navigatie Items',
            'label' => 'Navigatie',
            'route' => 'develop.nav.index',
        ]
    ];

    protected $crudItems = [
        ['studies', 'Studie', 'Studies', ['fa' => 'graduation-cap']],
        ['budgets', 'Budget', 'Budgetten'],
        ['events', 'Evenement', 'Evenementen'],
        ['groups', 'Groep', 'Groepen'],
        ['group-categories', 'Groepcategorie', 'Groepcategorieen'],
        ['persons', 'Persoon', 'Personen']
    ];

    /**
     * @var Request
     */
    protected $request;

    /**
     * NavigationItemRepository constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        foreach ($this->crudItems as $crudItem) {
            $this->addCrudItem(...$crudItem);
        }
    }

    protected function addCrudItem($table, $singular, $plural, $icons = []) {

        // Base item
        $this->items["crud.$table"] = [
            'name' => "$plural Beheren",
            'label' => "$plural",
            'route' => "$table.index",
            'icons' => $icons
        ];

        // Index item
        $this->items["crud.$table.index"] = [
            'name' => "$plural Lijst",
            'label' => "Lijst",
            'route' => "$table.index"
        ];

        // Create item
        $this->items["crud.$table.create"] = [
            'name' => "$singular Toevoegen",
            'label' => "Toevoegen",
            'route' => "$table.create"
        ];

        // Edit item
        $this->items["crud.$table.edit"] = [
            'name' => "$singular Bewerken",
            'label' => "Bewerken",
            'route' => "$table.edit"
        ];

        // Delete item
        $this->items["crud.$table.delete"] = [
            'name' => "$singular Verwijderen",
            'label' => "Verwijderen",
            'route' => "$table.delete"
        ];

    }

    /**
     * Returns a registered item based on the given input.
     *
     * @param mixed $item
     * @return NavigationItem|null
     */
    public function get($item)
    {
        if (is_string($item)) {
            return $this->getFromId($item);
        }

        if($item instanceof NavigationItem) {
            if($this->idExists($item->id)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Returns a list of all the NavigationItems in this repository
     */
    public function list() {
        foreach ($this->items as $id => $item) {
            $this->getFromId($id);
        }

        return $this->items;
    }

    /**
     * Returns a item based on the given input. Throws an error if the item could not be found.
     *
     * @param mixed $item
     * @return NavigationItem
     * @throws NavigationItemNotFoundException
     */
    public function require($item)
    {
        $res = $this->getFromId($item);

        if ($res === null) {
            throw new NavigationItemNotFoundException($item, $this);
        }

        return $res;
    }

    /**
     * Returns a navigation item from the specified $id. If no item with the given $id was found, null
     * is returned instead.
     *
     * @param string $id
     * @return NavigationItem|null
     */
    protected function getFromId($id)
    {
        if (!$this->idExists($id)) {
            return null;
        }

        $item = $this->items[$id];

        if(is_array($item)) {
            $item = new NavigationItem($id, $item, $this->request);
            $this->items[$id] = $item;
        }

        if($item instanceof NavigationItem) {
            return $item;
        }

        return null;
    }

    /**
     * Returns if there exists a NavigationItem with the provided $id.
     *
     * @param $id
     * @return boolean
     */
    protected function idExists($id) {
        return array_key_exists($id, $this->items);
    }

}