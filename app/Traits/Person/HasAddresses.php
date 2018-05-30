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
 *
 * @property-read PersonAddress[] $addresses
 */
trait HasAddresses
{
    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the first primary address of this Person.
     *
     * @return PersonAddress|null
     */
    public function getAddressAttribute() {
        $res = $this->addresses()->orderByDesc('is_primary')
                                 ->where('for_emergency','=', false)
                                 ->orderBy('label')->first();

        if($res instanceof PersonAddress) {
            return $res;
        } else {
            return null;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all the PersonAddresses that belong to this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses() {
        return $this->hasMany(PersonAddress::class, 'person_id')->orderBy('index');
    }


}