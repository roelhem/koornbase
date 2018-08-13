<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 09-05-18
 * Time: 18:19
 */

namespace App\Traits\Person;

use App\PersonAddress;

/**
 * Trait PersonHasAddresses
 *
 * Adds some functionality to a Person Model to manage the addresses.
 *
 * @package App\Traits
 *
 * @property-read PersonAddress|null $address
 * @property-read PersonAddress[] $addresses
 */
trait HasAddresses
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the primary address of this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address() {
        return $this->hasOne(PersonAddress::class, 'person_id')->orderBy('index');
    }

    /**
     * Gives all the PersonAddresses that belong to this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses() {
        return $this->hasMany(PersonAddress::class, 'person_id')->orderBy('index');
    }


}