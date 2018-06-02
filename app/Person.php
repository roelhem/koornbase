<?php

namespace App;

use App\Traits\HasRemarks;
use App\Traits\HasShortName;
use App\Traits\Person\HasAddresses;
use App\Traits\Person\HasEmailAddresses;
use App\Traits\Person\HasGroups;
use App\Traits\Person\HasMemberships;
use App\Traits\Person\HasName;
use App\Traits\Person\HasPhoneNumbers;
use App\Types\AvatarType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

/**
 * Class Person
 *
 * @package App
 *
 * @property integer $id
 * @property string|null $nickname
 * @property Carbon|null $birth_date
 *
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 * @property Carbon|null $deleted_at
 * @property integer|null $deleted_by
 *
 * @property-read string $avatar_letters
 * @property-read AvatarType $avatar
 * @property-read integer|null $age
 *
 * @property-read Collection $users
 * @property-read Collection $debtors
 */
class Person extends Model
{

    use SoftDeletes;
    use Userstamps;

    use HasRemarks;

    use HasName, HasMemberships, HasAddresses, HasPhoneNumbers, HasEmailAddresses, HasGroups;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'persons';

    protected $dates = ['birth_date','created_at','updated_at','deleted_at'];

    protected $fillable = ['name_first','name_middle','name_prefix','name_last','name_initials','name_nickname', 'birth_date','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the day when this person turns the given age.
     *
     * When $age is omitted, the next birthday of this person is returned. If no birth_date was found in this
     * Person, null will be returned.
     *
     * @param integer|null $age
     * @return Carbon|null
     */
    public function getBirthDay($age = null) {
        if($this->birth_date === null) {
            return null;
        }

        if($age === null) {
            $age = $this->birth_date->age() + 1;
        }

        $result = clone $this->birth_date;
        $result->addYears($age);

        return $result;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns two letters that can be used as a placeholder avatar of this person.
     *
     * @return string
     */
    public function getAvatarLettersAttribute() {
        $namePieces = explode(' ', trim($this->name));
        if(count($namePieces) === 1) {
            return mb_strtoupper(substr($namePieces[0], 0, 2));
        } elseif(count($namePieces) >= 2) {
            $firstWord = $namePieces[0];
            $secondWord = $namePieces[1];

            $firstLetter = substr(trim($firstWord),0,1);
            $secondLetter = substr(trim($secondWord), 0,1);

            return mb_strtoupper($firstLetter.$secondLetter);
        } else {
            return '??';
        }
    }

    /**
     * Returns a AvatarType that describes an Avatar that can be used in a front-end application to represent this
     * Person.
     *
     * @return AvatarType
     */
    public function getAvatarAttribute() {
        foreach ($this->users as $user) {
            if($user->avatar !== null) {
                return $user->avatar;
            }
        }
        $res = new AvatarType;
        $res->letters = $this->avatar_letters;
        return $res;
    }

    /**
     * Returns the age of this Person if the birth date is known.
     *
     * @return integer|null
     */
    public function getAgeAttribute() {
        $bd = $this->birth_date;

        if($bd === null) {
            return null;
        } else {
            return $bd->age;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the accounts of this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users() {
        return $this->hasMany(User::class, 'person_id');
    }

    /**
     * Gives all the Debtors that were assigned to this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function debtors() {
        return $this->hasMany(Debtor::class,'person_id');
    }

    /**
     * Gives all the KoornbeursCards that belong to this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards() {
        return $this->hasMany(KoornbeursCard::class, 'owner_id');
    }


}
