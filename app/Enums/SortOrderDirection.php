<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 20:56
 */

namespace App\Enums;


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

    const ASC = 'asc';
    const DESC = 'desc';

}