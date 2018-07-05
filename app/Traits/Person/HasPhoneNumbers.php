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
 * @property-read PersonPhoneNumber|null $phoneNumber
 * @property-read PersonPhoneNumber[] $phoneNumbers
 */
trait HasPhoneNumbers
{


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the primary PersonPhoneNumber of this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phoneNumber() {
        return $this->hasOne(PersonPhoneNumber::class, 'person_id')->orderBy('index');
    }

    /**
     * Gives all the PersonPhoneNumbers that belong to this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phoneNumbers() {
        return $this->hasMany(PersonPhoneNumber::class, 'person_id')->orderBy('index');
    }
}