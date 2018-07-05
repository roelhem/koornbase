<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-07-18
 * Time: 15:56
 */

namespace App\Contracts\Filters;
use App\Exceptions\Filters\FilterInvalidParametersException;
use App\Exceptions\Filters\FilterNotFoundException;

/**
 * Interface FilterProvider
 *
 * A class that takes the name and parameters of a filter from a request and returns a Closure that can be used
 * to filter a query.
 *
 * @package App\Contracts\Filters
 */
interface FilterProvider
{

    /**
     * Returns the filtering Closure that belongs to the provided $filter and $params.
     *
     * @param string $filterName
     * @param mixed $params
     * @return \Closure
     * @throws FilterNotFoundException
     * @throws FilterInvalidParametersException
     */
    public function get($filterName, $params);

    /**
     * Returns if this filter provider has a filter with the provided name.
     *
     * @param string $filterName
     * @return boolean
     */
    public function has($filterName);

    /**
     * Returns if the provided parameters are allowed for the filter with the provided $filterName.
     *
     * @param string $filterName
     * @param mixed $params
     * @return boolean
     * @throws FilterNotFoundException
     */
    public function allows($filterName, $params);

    /**
     * Returns a list of all the filterNames in this FilterProvider.
     *
     * @return array
     */
    public function list();

}