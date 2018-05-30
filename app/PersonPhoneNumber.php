<?php

namespace App;

use App\Traits\HasRemarks;
use App\Traits\BelongsToPerson;
use App\Traits\PersonContactEntry\HasContactOptions;
use App\Traits\PersonContactEntry\HasCountryCode;
use App\Traits\PersonContactEntry\OrderableWithIndex;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * Class PersonPhoneNumber
 *
 * @package App
 *
 * @property integer $id
 * @property string $label
 * @property boolean $is_mobile
 * @property PhoneNumber $phone_number
 *
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 */
class PersonPhoneNumber extends Model
{
    use Userstamps;

    use HasRemarks, BelongsToPerson;

    use HasContactOptions, OrderableWithIndex, HasCountryCode;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'person_phone_numbers';

    protected $fillable = ['label','country_code','is_mobile','phone_number','options','remarks'];

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
        return $this->phone_number->formatForCountry($this->country_code ?? 'NL');
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
        return PhoneNumber::make($value,  $this->country_code ?? 'NL');
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
            $value = PhoneNumber::make($value, $this->country_code ?? 'NL');
        }

        $this->attributes['phone_number'] = $value->formatE164();
    }
}
