<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 15:33
 */

namespace Roelhem\GraphQL\Types\OrderBy;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Roelhem\GraphQL\Contracts\OrderableContract;
use Roelhem\GraphQL\Enums\OrderByDirection;
use Roelhem\GraphQL\Paginators\Cursor;
use Roelhem\GraphQL\Paginators\CursorPattern;

class Orderable implements OrderableContract
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var null
     */
    protected $description;

    /**
     * @var OrderByDirection
     */
    protected $direction;


    /**
     * @var \Closure|array|string|null
     */
    protected $query;

    /**
     * @var CursorPattern
     */
    protected $cursorPattern;


    public function __construct($config = [])
    {

        $this->name = array_get($config, 'name', $this->name);
        $this->description = array_get($config, 'description', $this->description);
        $this->direction = OrderByDirection::by(array_get($config,'direction', $this->direction));

        if($this->direction->getValue() === OrderByDirection::ASC) {
            $this->description .= ' [`ASC`: In ascending order.]';
        } else {
            $this->description .= ' [`DESC`: In descending order.]';
        }


        $column = array_get($config,'column', $this->name);

        $this->query = array_get($config, 'query', $column);
        if(is_string($this->query)) {
            $this->query = [$this->query];
        }
        $this->cursorPattern = new CursorPattern(array_get($config, 'cursorPattern', []));
    }

    /**
     * Returns the name of the Orderable.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Returns the description of a Orderable.
     *
     * @return string|null
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * Returns the direction in which this Orderable is orientated.
     *
     * @return OrderByDirection
     */
    public function direction()
    {
        return $this->direction;
    }

    /**
     * Returns the cursor pattern that belongs to this orderable.
     */
    public function cursorPattern()
    {
        return $this->cursorPattern;
    }

    /**
     * Returns a definition for an Enum-value.
     *
     * @return array|\GraphQL\Type\Definition\EnumValueDefinition
     */
    public function enumValueDefinition()
    {
        return [
            'name' => $this->name()."_".$this->direction()->getName(),
            'description' => $this->description(),
            'value' => $this,
        ];
    }

    /**
     * Applies the orderBy rules on a certain query.
     *
     * @param Builder $query
     * @return Builder
     */
    public function applyToQuery($query)
    {
        $handler = $this->query;

        if(is_array($handler)) {
            foreach($handler as $handlerColumn) {
                $query = $query->orderBy($handlerColumn, $this->direction());
            }
            return $query;
        }

        if(is_callable($handler)) {
            return call_user_func($handler, $query, $this->direction());
        }

        return $query;
    }

    /**
     * Returns a string containing a cursor for the provided model.
     *
     * @param array|Model $model
     * @return string
     */
    public function getCursor($model)
    {
        if($model === null) {
            return null;
        }

        $cursor = $this->cursorPattern()->createFromModel($model);
        return $this->cursorPattern()->pack($cursor);
    }

    /**
     * @param string|null $cursor
     * @return Cursor
     */
    protected function unpackCursor($cursor = null) {
        if($cursor === null) {
            return null;
        }
        return $this->cursorPattern()->unpack($cursor);
    }

    /**
     * Applies the afterCursor rule with the provided cursor.
     *
     * @param Builder $query
     * @param string|null $cursor
     * @return Builder
     */
    public function applyAfterCursor($query, $cursor = null)
    {
        $cursor = $this->unpackCursor($cursor);
        if($cursor === null) {
            return $query;
        }

        if (is_array($this->query)) {
            $fields = $this->query;
            $closure = null;
            foreach (array_reverse($fields) as $field) {
                $value = $cursor->get($field);
                $closure = function($query) use ($field, $value, $closure) {
                    /** @var Builder $query */
                    $query = $query->where($field, $this->direction()->operator(), $value);
                    if($closure !== null) {
                        $query = $query->orWhere($field, '=', $value)->where($closure);
                    }
                    return $query;
                };
            }
            return $query->where($closure);
        }

        return $query;
    }
}