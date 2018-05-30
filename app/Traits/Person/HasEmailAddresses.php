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
 * @property-read PersonEmailAddress[] $phoneNumbers
 */
trait HasEmailAddresses
{
    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the first primary email address of this Person.
     *
     * @return PersonEmailAddress|null
     */
    public function getEmailAddressAttribute() {
        $res = $this->emailAddresses()->orderByDesc('is_primary')
                                      ->where('for_emergency','=', false)
                                      ->orderBy('label')->first();

        if($res instanceof PersonEmailAddress) {
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
    public function emailAddresses() {
        return $this->hasMany(PersonEmailAddress::class, 'person_id')->orderBy('index');
    }
}