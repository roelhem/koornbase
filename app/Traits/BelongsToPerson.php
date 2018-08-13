<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 14:16
 */

namespace App\Traits;


use App\Person;
use Illuminate\Database\Eloquent\Builder;

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

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTS: OwnedByPerson -------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the owner of this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * @inheritdoc
     */
    public function getOwner()
    {
        return $this->person;
    }

    /**
     * @inheritdoc
     */
    public function getOwnerId()
    {
        return $this->person_id;
    }

    /**
     * Scope that filters only the objects that are owned by the Person with the provided id.
     *
     * @param Builder $query
     * @param integer $person_id
     * @return Builder
     */
    public function scopeOwnedBy($query, $person_id) {
        return $query->where('person_id', '=', $person_id);
    }

}