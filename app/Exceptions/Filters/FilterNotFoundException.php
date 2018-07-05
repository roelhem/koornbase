<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-07-18
 * Time: 16:01
 */

namespace App\Exceptions\Filters;

/**
 * Class FilterNotFoundException
 *
 * An exception that is thrown when a FilterProvider can't find a filter with the provided filter-name.
 *
 * @package App\Exceptions\Filters
 */
class FilterNotFoundException extends \Exception
{

}