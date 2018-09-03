<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 03:23
 */

namespace App\Enums;


use MabeEnum\Enum;

/**
 * Class GraphQLOperationType
 * @method static GraphQLOperationType QUERY()
 * @method static GraphQLOperationType MUTATION()
 * @method static GraphQLOperationType SUBSCRIPTION()
 */
final class GraphQLOperationType extends Enum
{
    const QUERY = 'query';
    const MUTATION = 'mutation';
    const SUBSCRIPTION = 'subscription';

    public static function getDefault() {
        return self::QUERY();
    }
}