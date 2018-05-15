<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 01-05-18
 * Time: 22:41
 */

namespace App\Exceptions;


use App\Services\Navigation\NavigationItem;
use App\Services\Navigation\NavigationItemRepository;
use Throwable;

/**
 * Class NodeNotFoundException
 *
 * An exception that can be thrown if a certain item cannot be found.
 *
 * @package App\Exceptions
 */
class NavigationItemNotFoundException extends \Exception
{

    public $item;
    public $repository;

    public function __construct($item = null, NavigationItemRepository $repository = null, int $code = 0, Throwable $previous = null)
    {
        $this->item = $item;
        $this->repository = $repository;

        parent::__construct($this->constructMessage($item), $code, $previous);
    }


    private function constructMessage($item) {
        if($item === null) {
            $item = '';
        }
        if($item instanceof NavigationItem) {
            $item = $item->id;
        }

        return "The requested NavigationItem $item could not be found in the NavigationItemRepository.";
    }

}