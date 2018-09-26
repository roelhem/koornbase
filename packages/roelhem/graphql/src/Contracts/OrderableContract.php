<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 15:34
 */

namespace Roelhem\GraphQL\Contracts;


use GraphQL\Type\Definition\EnumValueDefinition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Roelhem\GraphQL\Enums\OrderByDirection;
use Roelhem\GraphQL\Paginators\CursorPattern;

interface OrderableContract
{

    /**
     * Returns the name of the Orderable.
     *
     * @return string
     */
    public function name();

    /**
     * Returns the description of a Orderable.
     *
     * @return string|null
     */
    public function description();

    /**
     * Returns the direction in which this Orderable is orientated.
     *
     * @return OrderByDirection
     */
    public function direction();

    /**
     * Returns an array or EnumValueDefinition that represents this orderable.
     *
     * @return array|EnumValueDefinition
     */
    public function enumValueDefinition();

    /**
     * Returns the Cursor-pattern that belong to this orderable.
     *
     * @return CursorPattern
     */
    public function cursorPattern();

    /**
     * Applies the orderBy rules on a certain query.
     *
     * @param Builder $query
     * @return Builder
     */
    public function applyToQuery($query);

    /**
     * Returns a string containing a cursor for the provided model.
     *
     * @param array|Model $model
     * @return string
     */
    public function getCursor($model);

    /**
     * Applies the afterCursor rule with the provided cursor.
     *
     * @param Builder $query
     * @param string|null $cursor
     * @return Builder
     */
    public function applyAfterCursor($query, $cursor = null);


}