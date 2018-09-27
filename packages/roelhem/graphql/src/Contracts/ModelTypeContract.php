<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 14:08
 */

namespace Roelhem\GraphQL\Contracts;


use Roelhem\GraphQL\Types\Filters\FilterInputType;
use Roelhem\GraphQL\Types\OrderBy\OrderByInputType;

/**
 * Interface ModelTypeContract
 * @package Roelhem\GraphQL\Contracts
 *
 * @property-read string $name
 */
interface ModelTypeContract
{

    /**
     * Gives the name of the Model that this ModelType represents.
     *
     * @return string
     */
    public function getModelClass();

    /**
     * Gives if you can order a list of this ModelTypes.
     *
     * @return boolean
     */
    public function orderable();

    /**
     * Returns the OrderByInputType that belongs to this Model-type.
     *
     * @return OrderByInputType
     */
    public function getOrderByInputType();

    /**
     * Gives if you can use filters on a list of this ModelTypes.
     *
     * @return boolean
     */
    public function filterable();

    /**
     * Gives the filter input type that belongs to this Model-type.
     *
     * @return FilterInputType
     */
    public function getFilterInputType();

    /**
     * Gives if you can text-based-search on this ModelType.
     *
     * @return boolean
     */
    public function searchable();

}