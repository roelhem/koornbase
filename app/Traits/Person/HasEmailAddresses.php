<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 09-05-18
 * Time: 20:22
 */

namespace App\Traits\Person;


use App\PersonEmailAddress;

/**
 * Trait HasEmailAddresses
 *
 *
 * @package App\Traits\Person
 *
 * @property-read PersonEmailAddress|null $email_address
 *
 * @property-read PersonEmailAddress[] $emailAddresses
 */
trait HasEmailAddresses
{


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the primary E-mail address of this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function emailAddress() {
        return $this->hasOne(PersonEmailAddress::class, 'person_id')->orderBy('index');
    }

    /**
     * Gives all the E-mail addresses that belong to this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emailAddresses() {
        return $this->hasMany(PersonEmailAddress::class, 'person_id')->orderBy('index');
    }
}