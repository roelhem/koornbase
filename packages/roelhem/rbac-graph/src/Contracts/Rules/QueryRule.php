<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 13:59
 */

namespace Roelhem\RbacGraph\Contracts\Rules;
use Illuminate\Database\Eloquent\Builder;


/**
 * Query QueryRule
 *
 * A rule that can filter all the models that conform to this rule as a sql-query.
 *
 * @package Roelhem\RbacGraph\Contracts\Rules
 */
interface QueryRule extends ModelRule
{


    /**
     * Adds where-clauses to the provided query such that only models that conform to this rule will be shown.
     *
     * @param Builder $query
     * @param RuleAttributeBag $bag
     * @return Builder
     */
    public function queryFilter($query, $bag);

}