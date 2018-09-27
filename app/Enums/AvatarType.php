<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 13:19
 */

namespace App\Enums;


use App\Person;
use App\User;
use App\UserAccount;
use MabeEnum\Enum;
use Roelhem\GraphQL\Facades\GraphQL;

/**
 * Class AvatarType
 * @package App\Enums
 *
 * @method static AvatarType USER()
 * @method static AvatarType PERSON()
 * @method static AvatarType EXTERNAL()
 *
 */
final class AvatarType extends Enum
{
    const USER = User::class;
    const PERSON = Person::class;
    const EXTERNAL = UserAccount::class;

    public function getGraphQLTypeName()
    {
        switch ($this->getValue()) {
            case self::USER: return 'UserAvatar';
            case self::PERSON: return 'PersonAvatar';
            case self::EXTERNAL: return 'ExternalAvatar';
            default: return 'Avatar';
        }
    }
}