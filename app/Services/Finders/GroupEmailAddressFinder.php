<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 07:10
 */

namespace App\Services\Finders;


use App\Exceptions\Finders\ModelNotFoundException;
use App\GroupEmailAddress;

class GroupEmailAddressFinder extends ModelByIdFinder
{

    const EMAIL_REGEX = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

    /**
     * GroupEmailAddressFinder constructor.
     */
    public function __construct()
    {
        parent::__construct('group_email_address', GroupEmailAddress::class);
    }

    /**
     * @inheritdoc
     */
    public function accepts($input): bool
    {
        if(parent::accepts($input)) {
            return true;
        }

        if(preg_match(self::EMAIL_REGEX, $input) === 1) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function find($input)
    {
        if(parent::accepts($input)) {
            return parent::find($input);
        }

        $model = null;
        if(preg_match(self::EMAIL_REGEX, $input) === 1) {
            $model = GroupEmailAddress::query()->where('email_address','=',$input)->first();
        }

        if($model instanceof GroupEmailAddress) {
            return $model;
        } else {
            throw new ModelNotFoundException();
        }
    }
}