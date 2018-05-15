<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Carbon\Carbon;
use Illuminate\Database\Schema\Builder;


/**
 * Class PersonEmailAddress
 *
 * @package App
 *
 * @property integer $id
 * @property integer $person_id
 * @property string $label
 * @property boolean $is_primary
 * @property boolean $for_emergency
 * @property string $email_address
 *
 * @property string|null $remarks
 *
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 *
 * @property-read Person $person
 * @property-read string $link
 */
class PersonEmailAddress extends Model
{
    use Userstamps;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'person_email_addresses';

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MAGIC METHODS -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the email_address as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->email_address ?? '(onbekend)';
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePrimary($query) {
        return $query->where('is_primary');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Person where this PersonEmailAddress belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
