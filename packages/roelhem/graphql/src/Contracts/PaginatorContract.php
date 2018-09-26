<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 17:49
 */

namespace Roelhem\GraphQL\Contracts;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Roelhem\GraphQL\Enums\PaginationType;

interface PaginatorContract
{

    /**
     * @return PaginationType
     */
    public function type();

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ITEM INSTANCES ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns a collection of all the items on the current page.
     *
     * @return Collection
     */
    public function items();

    /**
     * Returns the first item of the page.
     *
     * @return mixed
     */
    public function startItem();

    /**
     * Returns the last item of the page.
     *
     * @return mixed
     */
    public function endItem();

    /**
     * Returns the total amount of items on the current page.
     *
     * @return integer|null
     */
    public function count();

    /**
     * Returns the total number of items in the list.
     *
     * @return integer
     */
    public function total();

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INDEX NUMBERS -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @return integer|null
     */
    public function startIndex();

    /**
     * @return integer|null
     */
    public function endIndex();

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CURSORS -------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @param Model|array|mixed
     * @return string|null
     */
    public function getCursor($item);

    /**
     * @return string|null
     */
    public function startCursor();

    /**
     * @return string|null
     */
    public function endCursor();

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- PAGES ---------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the maximum amount of items on one page.
     *
     * @return integer
     */
    public function perPage();

    /**
     * Returns the number of the current page.
     *
     * @return integer|null
     */
    public function currentPage();

    /**
     * Returns the number of the last page.
     *
     * @return integer|null
     */
    public function lastPage();

    /**
     * Returns if the items in the list couldn't fit on just one page.
     *
     * @return boolean
     */
    public function hasPages();

    /**
     * Returns if there are more pages after the current one.
     *
     * @return boolean
     */
    public function hasMorePages();

}