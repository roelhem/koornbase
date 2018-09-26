<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 11:53
 */

namespace App\Enums;


use App\Person;
use App\Types\Name;
use MabeEnum\Enum;

/**
 * Class PersonNameFormat
 * @package App\Enums
 *
 * @method static PersonNameFormat NORMAL()
 * @method static PersonNameFormat SHORT()
 * @method static PersonNameFormat FULL()
 * @method static PersonNameFormat FORMAL()
 * @method static PersonNameFormat SORTABLE()
 * @method static PersonNameFormat LAST_NAME()
 * @method static PersonNameFormat GIVEN_NAME()
 */
final class PersonNameFormat extends Enum
{
    const NORMAL     = 'NORMAL';
    const SHORT      = 'SHORT';
    const FULL       = 'FULL';
    const FORMAL     = 'FORMAL';
    const SORTABLE   = 'SORTABLE';
    const LAST_NAME  = 'LAST_NAME';
    const GIVEN_NAME = 'GIVEN_NAME';


    /**
     * @return PersonNameFormat
     */
    public static function default() {
        return self::NORMAL();
    }



    // ---------------------------------------------------------------------------------------------------------- //
    // --------  NAME FORMATTER  -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @param Name $name
     * @return string
     */
    public function format($name) {

        switch ($this->getName()) {
            case self::LAST_NAME:
                /**
                 * Get the full last_name.
                 */
                if(empty($name->name_prefix)) {
                    return trim($name->name_last);
                } else {
                    return trim($name->name_prefix).' '.trim($name->name_last);
                }

            case self::GIVEN_NAME:
                /**
                 * Gets the first name and middle names.
                 */
                return trim(trim($name->name_first).' '.trim($name->name_middle));

            case self::NORMAL:
                /**
                 * Gets the first name and the last name.
                 */
                $first = $name->name_first ?? $name->name_nickname ?? $name->name_initials ?? $name->name_middle;
                return trim($first).' '.self::LAST_NAME()->format($name);


            case self::SHORT:
                /**
                 * Get a short representation of the name.
                 */
                if(!empty($name->name_nickname)) {
                    return trim($name->name_nickname);
                } elseif(!empty($name->name_first)) {
                    return trim($name->name_first);
                } else {
                    return self::LAST_NAME()->format($name);
                }

            case self::FULL:
                /**
                 * Gets the full name.
                 */
                return trim(self::GIVEN_NAME()->format($name).' '.self::LAST_NAME()->format($name));


            case self::FORMAL:
                /**
                 * Gets the formal repesentation of the name.
                 */
                $first = $name->name_initials ?? $name->name_first ?? $name->name_middle ?? $name->name_nickname;

                return trim($first).' '.self::LAST_NAME()->format($name);

            case self::SORTABLE:
                /**
                 * A format for sorting
                 */
                return trim(trim($name->name_last).', '.trim($name->name_first).' '.trim($name->name_prefix));
        }

    }
}