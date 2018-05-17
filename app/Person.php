<?php

namespace App;

use App\Traits\Person\HasAddresses;
use App\Traits\Person\HasCardOwnerships;
use App\Traits\Person\HasEmailAddresses;
use App\Traits\Person\HasGroupMemberships;
use App\Traits\Person\HasMemberships;
use App\Traits\Person\HasPhoneNumbers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

/**
 * Class Person
 *
 * @package App
 *
 * @property integer|null $id
 * @property string|null $name_initials
 * @property string|null $name_first
 * @property string|null $name_middle
 * @property string|null $name_prefix
 * @property string|null $name_last
 * @property string|null $name_nickname
 * @property Carbon|null $birth_date
 * @property string|null $bsn
 * @property string|null $remarks
 *
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 * @property Carbon|null $deleted_at
 * @property integer|null $deleted_by
 *
 * @property-read string $name
 * @property-read string $name_short
 * @property-read string $name_formal
 * @property-read string $avatar_letters
 * @property-read string|null $avatar
 * @property-read integer|null $age
 *
 * @property array $name_array
 *
 * @property-read User[] $users
 * @property-read Debtor[] $debtors
 * @property-read Job[] $jobs
 */
class Person extends Model
{

    use SoftDeletes;
    use Userstamps;

    use HasMemberships, HasGroupMemberships, HasAddresses, HasPhoneNumbers, HasEmailAddresses, HasCardOwnerships;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'persons';

    protected $dates = ['birth_date','created_at','updated_at','deleted_at'];

    protected $fillable = [
            'name_initials','name_first','name_middle','name_prefix','name_last','name_nickname',
            'birth_date','bsn','remarks','name_array'
        ];

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
     * Returns the full name of this person as a string. This string can be used for display purposes.
     *
     * @return string
     */
    public function getNameAttribute() {
        $result = $this->name_first;

        if ($this->name_prefix) {
            $result .= ' '.$this->name_prefix;
        }

        return $result.' '.$this->name_last;
    }

    /**
     * Returns all the name parts in one array.
     */
    public function getNameArrayAttribute() {
        return [
            'full' => $this->name,
            'short' => $this->name_short,
            'formal' => $this->name_formal,
            'initials' => $this->name_initials,
            'first' => $this->name_first,
            'middle' => $this->name_middle,
            'prefix' => $this->name_prefix,
            'last' => $this->name_last,
            'nickname' => $this->name_nickname,
        ];
    }

    /**
     * Returns a short name of this person. This string can be used for display purposes.
     *
     * @return string
     */
    public function getNameShortAttribute() {
        if(!empty($this->name_nickname)) {
            return $this->name_nickname;
        } else {
            return $this->name_first;
        }
    }

    /**
     * Returns the name of this person in a formal way.
     *
     * @return string
     */
    public function getNameFormalAttribute() {
        $res = $this->name_initials;

        if($this->name_prefix) {
            $res .= ' '.$this->name_prefix;
        }

        $res .= ' '.$this->name_last;

        return $res;
    }


    /**
     * Returns two letters that can be used as a placeholder avatar of this person.
     *
     * @return string
     */
    public function getAvatarLettersAttribute() {
        $firstLetter = substr(trim($this->name_first),0,1);
        $secondLetter = substr(trim($this->name_last), 0,1);

        return mb_strtoupper($firstLetter.$secondLetter);
    }

    /**
     * Returns a hyperlink to an image that can be used as an image for this persons Avatar.
     *
     * @return string|null
     */
    public function getAvatarAttribute() {
        foreach ($this->users as $user) {
            if($user->avatar !== null) {
                return $user->avatar;
            }
        }
        return null;
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
    // ----- CUSTOM MUTATORS ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Sets the name of this person by using an array of name parts.
     *
     * @param array|null $newValue
     */
    public function setNameArrayAttribute($newValue) {
        if(is_array($newValue)) {

            $map = [
                'initials' => 'name_initials',
                'first' => 'name_first',
                'middle' => 'name_middle',
                'prefix' => 'name_prefix',
                'last' => 'name_last',
                'nickname' => 'name_nickname',
            ];

            foreach ($map as $arrayKey => $attributeKey) {
                if(array_has($newValue, $arrayKey)) {
                    $this->$attributeKey = array_get($newValue, $arrayKey);
                }
            }

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
     * Gives the Jobs that this Person have fulfilled or should fulfill.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs() {
        return $this->hasMany(Job::class, 'person_id');
    }

    /**
     * Gives the Events that have this Person as the manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function managedEvents() {
        return $this->hasMany(Event::class, 'manager_id');
    }


}
