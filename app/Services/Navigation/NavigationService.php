<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 01-05-18
 * Time: 13:58
 */

namespace App\Services\Navigation;


use Countable;
use IteratorAggregate;
use ArrayIterator;
use App\Exceptions\NavigationItemNotFoundException;
use Illuminate\Http\Request;

abstract class NavigationService implements IteratorAggregate, Countable
{

    /**
     * @var NavigationNode[] The first children of the tree structure.
     */
    protected $tree = [];

    /**
     * @var Request The current Http-request.
     */
    protected $request;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INITIALIZATION ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * NavigationService constructor.
     *
     * @param Request $request The current Http-request.
     * @throws NavigationItemNotFoundException
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        foreach ($this->init() as $key => $value) {
            if(is_string($key)) {
                $this->tree[] = new NavigationNode($key, $value);
            } else {
                $this->tree[] = new NavigationNode($value);
            }
        }
    }

    /**
     * The array that initializes the tree structure.
     *
     * @return array
     */
    abstract protected function init();

    /**
     * Returns the tree.
     */
    public function tree() {
        return $this->tree;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->tree);
    }

    public function count()
    {
        return count($this->tree);
    }

    public function pathTo($id)
    {
        foreach ($this as $child) {
            $path = $child->pathTo($id);

            if($path) { return $path; }
        }

        return [];
    }

    public function pathToCurrent()
    {
        foreach ($this as $child) {
            $path = $child->pathToCurrent();

            if($path) { return $path; }
        }

        return [];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- STRUCTURE CREATION: bootstrap navigation. ---------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the information from tree() in a format to make it easier to construct a Bootstrap navbar.
     *
     * @param int|null $maxNested The maximum number of nested items to return.
     * @param array|null $items The items that need to be converted. When null is given, the items value of tree
     *                          will be used.
     * @return array
     */
    /*
    public function navArray($maxNested = null, $items = null)
    {
        if($items === null) {
            $items = $this['items'];
        }

        $result = [];

        foreach($items as $item) {

            // Initialize some helping variables
            $subItems = [];
            $itemClasses = $this->navItemClasses;
            $linkClasses = $this->navLinkClasses;

            $active = $this->isActive($item);
            if($active) {
                $linkClasses[] = 'active';
            }

            // nested items
            if($maxNested === null || $maxNested > 0) {
                $nestedItems = array_get($item, 'items');
                if($nestedItems !== null && count($nestedItems) > 0) {
                    //recursive call
                    $itemClasses[] = 'dropdown';
                    $nextMaxNested = ($maxNested === null) ? null : $maxNested-1;
                    $subItems = $this->navArray($nextMaxNested, $nestedItems);
                }
            }

            // Constructing the item
            $result[] = [
                'label' => array_get($item, 'label',''),
                'href' => $this->getHref($item),
                'icon' => $this->getIconClass($item, 'fe'),
                'active' => $active,
                'class' => implode(' ', $itemClasses),
                'linkClass' => implode(' ', $linkClasses),
                'hasItems' => count($subItems) > 0,
                'items' => $subItems,
            ];
        }

        return $result;
    }*/


}