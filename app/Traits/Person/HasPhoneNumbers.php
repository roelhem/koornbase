<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 09-05-18
 * Time: 19:29
 */

namespace App\Traits\Person;


use App\PersonPhoneNumber;

/**
 * Trait HasPhoneNumbers
 *
 *
 * @package App\Traits\Person
 *
 * @property-read PersonPhoneNumber|null $phone_number
 *
 * @property-read PersonPhoneNumber[] $phoneNumbers
 */
trait HasPhoneNumbers
{
    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the first primary phone number of this Person.
     *
     * @return PersonPhoneNumber|null
     */
    public function getPhoneNumberAttribute() {
        $res = $this->phoneNumbers()->orderByDesc('is_primary')
                                    ->where('for_emergency','=', false)
                                    ->orderBy('label')->first();

        if($res instanceof PersonPhoneNumber) {
            return $res;
        } else {
            return null;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all the PersonPhoneNumbers that belong to this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phoneNumbers() {
        return $this->hasMany(PersonPhoneNumber::class, 'person_id')->orderBy('index');
    }
}