<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\Rules\Phone;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Query\Builder;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * Class PersonPhoneNumber
 *
 * @package App
 *
 * @property integer $id
 * @property integer $person_id
 * @property string $label
 * @property boolean $is_primary
 * @property boolean $for_emergency
 * @property boolean $is_mobile
 * @property PhoneNumber $phone_number
 *
 * @property string|null $remarks
 *
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 */
class PersonPhoneNumber extends Model
{
    use Userstamps;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'person_phone_numbers';

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MAGIC METHODS -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the phone_number as a string.
     *
     * @return string
     * @throws
     */
    public function __toString()
    {
        return $this->phone_number->formatForCountry('NL');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the phone_number as a PhoneNumber
     *
     * @param $value
     * @return PhoneNumber
     */
    public function getPhoneNumberAttribute($value) {
        return PhoneNumber::make($value,  'NL');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM MUTATORS ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Saves the phone_number in the right format.
     *
     * @param $value
     */
    public function setPhoneNumberAttribute($value) {
        if(!($value instanceof PhoneNumber)) {
            $value = PhoneNumber::make($value, 'NL');
        }

        $this->attributes['phone_number'] = $value->formatE164();
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
     * Gives the Person where this PersonPhoneNumber belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
