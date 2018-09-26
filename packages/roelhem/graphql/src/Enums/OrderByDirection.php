<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 15:50
 */

namespace Roelhem\GraphQL\Enums;


use MabeEnum\Enum;

/**
 * Class OrderByDirection
 * @package Roelhem\GraphQL\Enums
 *
 *
 * @method static OrderByDirection ASC()
 * @method static OrderByDirection DESC()
 */
final class OrderByDirection extends Enum
{
    // ---------------------------------------------------------------------------------------------------------- //
    // --------  ENUM-VALUE DEFINITIONS  ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    const ASC = 'asc';
    const DESC = 'desc';


    // ---------------------------------------------------------------------------------------------------------- //
    // --------  Operation METHODS  ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the inverted direction of this direction.
     * @return OrderByDirection
     */
    public function invert()
    {
        switch ($this->getValue()) {
            case self::ASC: return self::DESC();
            case self::DESC: return self::ASC();
            default: return $this;
        }
    }

    public function operator()
    {
        switch ($this->getValue()) {
            case self::ASC: return '>';
            case self::DESC: return '<';
            default: return '=';
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
     * @return OrderByDirection
     */
    public static function default() {
        return self::ASC();
    }

    /**
     * Returns a SortOrderDirection based on the provided input.
     *
     * @param mixed $input
     * @return OrderByDirection
     */
    public static function by($input) {
        if($input instanceof OrderByDirection) {
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

        return OrderByDirection::get($input);
    }
}