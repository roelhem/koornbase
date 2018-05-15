<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-05-18
 * Time: 21:03
 */

namespace App\Traits\Person;
use App\KoornbeursCardOwnership;
use Carbon\Carbon;

/**
 * Trait HasCardOwnerships
 * @package App\Traits\Person
 */
trait HasCardOwnerships
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the KoornbeursCardOwnerships of this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cardOwnerships() {
        return $this->hasMany(KoornbeursCardOwnership::class, 'person_id');
    }

    /**
     * Gives the current KoornbeursCardOwnerships of this Person.
     *
     * @param Carbon|string|null $at
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function currentCardOwnerships($at = null) {
        return $this->cardOwnerships()->now($at);
    }

    /**
     * Gives the most recent KoornbeursCardOwnership of this Person that has not ended yet.
     *
     * @param Carbon|string|null $at
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cardOwnership($at = null) {
        return $this->hasOne(KoornbeursCardOwnership::class, 'person_id')->now($at)->orderByDesc('start');
    }

}