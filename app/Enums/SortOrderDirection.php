<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 20:56
 */

namespace App\Enums;


use App\Enums\Traits\HasGraphQLEnumType;
use MabeEnum\Enum;

/**
 * Class SortDirection
 *
 * @package App\Enums
 *
 * @method static SortOrderDirection ASC()
 * @method static SortOrderDirection DESC()
 */
final class SortOrderDirection extends Enum
{

    use HasGraphQLEnumType;

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  ENUM-VALUE DEFINITIONS  ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    const ASC = 'asc';
    const DESC = 'desc';

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  GraphQL OPTIONS  ------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    protected static function getDescription()
    {
        return 'This `Enum`-type represent the two different ways to order a sorted list.';
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  Operation METHODS  ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the inverted direction of this direction.
     * @return SortOrderDirection
     */
    public function invert()
    {
        switch ($this->getValue()) {
            case self::ASC: return self::DESC();
            case self::DESC: return self::ASC();
            default: return $this;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  STATIC PARSER METHODS  ------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected static $ascValues = ['ascending','a', '+'];
    protected static $descValues = ['descending','d', '-'];

    /**
     * Returns the default sorting order.
     *
     * @return SortOrderDirection
     */
    public static function default() {
        return self::ASC();
    }

    /**
     * Returns a SortOrderDirection based on the provided input.
     *
     * @param mixed $input
     * @return SortOrderDirection
     */
    public static function by($input) {
        if($input instanceof SortOrderDirection) {
            return $input;
        }
        if(empty($input)) {
            return self::default();
        }
        if(is_numeric($input)) {
            if($input < 0) {
                return self::DESC();
            } else {
                return self::ASC();
            }
        }
        if(is_string($input)) {
            $input = mb_strtolower($input);
            if(in_array($input, self::$ascValues) || $input === self::ASC) {
                return self::ASC();
            } elseif(in_array($input, self::$descValues) || $input === self::DESC) {
                return self::DESC();
            } else {
                throw new \InvalidArgumentException("Can't convert '$input' to a sort-order.");
            }
        }

        return SortOrderDirection::get($input);
    }

}