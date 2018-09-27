<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 11:59
 */

namespace App\Types;


use App\Person;
use App\User;
use Illuminate\Support\Fluent;

/**
 * Class Avatar
 * @package App\Types
 *
 * @property string|null $image
 * @property string|null $letters
 *
 * @property User|null $user
 * @property Person|null $person
 */
class Avatar extends Fluent
{

    /**
     * Returns the type of this Avatar.
     *
     * @return \App\Enums\AvatarType|null
     */
    public function getType() {
        if($this->get('person') instanceof Person) {
            return \App\Enums\AvatarType::PERSON();
        } elseif($this->get('user') instanceof User) {
            return \App\Enums\AvatarType::USER();
        }
        return null;
    }

    /**
     * Creates a new Avatar based on a Person.
     *
     * @param Person $person
     * @return Avatar
     */
    public static function createForPerson(Person $person)
    {
        // Determine the letters.
        $firstLetter = mb_substr(trim($person->name_first), 0,1);
        $lastLetter = mb_substr(trim($person->name_last), 0,1);
        $letters = mb_strtoupper($firstLetter.$lastLetter);

        // Create the Avatar
        return new Avatar([
            'letters' => $letters,
            'person' => $person,
        ]);
    }

    /**
     * Creates a new Avatar based on a User.
     *
     * @param User $user
     * @return Avatar
     */
    public static function createForUser(User $user)
    {
        $person = $user->person;
        if($person instanceof Person) {
            // Expand the Person avatar.
            $avatar = self::createForPerson($person);
            $avatar->user = $user;
            return $avatar;
        } else {
            // Determine the letters.
            $name = $user->name;
            $letters = mb_strtolower(mb_substr(trim($name), 0, 2)).'.';

            // Create the Avatar.
            return new Avatar([
                'letters' => $letters,
                'user' => $user,
            ]);
        }


    }
}