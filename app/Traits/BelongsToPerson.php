<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 14:16
 */

namespace App\Traits;


use App\Person;

/**
 * Trait BelongsToPerson
 *
 * @package App\Traits\PersonContactEntry
 *
 * @property integer $person_id
 *
 * @property-read Person $person
 */
trait BelongsToPerson
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Person where this Model belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }

}