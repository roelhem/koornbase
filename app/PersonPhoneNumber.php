<?php

namespace App;

use App\Contracts\OwnedByPerson;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\HasRemarks;
use App\Traits\BelongsToPerson;
use App\Traits\PersonContactEntry\HasContactOptions;
use App\Traits\PersonContactEntry\HasCountryCode;
use App\Traits\PersonContactEntry\OrderableWithIndex;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;
use Wildside\Userstamps\Userstamps;

/**
 * Class PersonPhoneNumber
 *
 * @package App
 * @property integer $id
 * @property string $label
 * @property boolean $is_mobile
 * @property PhoneNumber $phone_number
 * @property PhoneNumberType $number_type
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 * @property-read mixed $country
 * @property string $country_code
 * @property \OptionsType $options
 * @property-read \App\Person $owner
 * @property-read \App\Person $person
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonPhoneNumber filter($input = array(), $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonPhoneNumber ownedBy($person_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonPhoneNumber paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonPhoneNumber simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonPhoneNumber sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonPhoneNumber sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonPhoneNumber whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonPhoneNumber whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonPhoneNumber whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonPhoneNumber whereOptions($options)
 * @mixin \Eloquent
 */
class PersonPhoneNumber extends Model implements OwnedByPerson
{
    use Userstamps;
    use Filterable, Sortable, Searchable;

    use HasRemarks, BelongsToPerson;

    use HasContactOptions, OrderableWithIndex, HasCountryCode;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'person_phone_numbers';

    protected $fillable = ['label','country_code','phone_number','options','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MAGIC METHODS -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @var PhoneNumberUtil
     */
    protected $util;

    /**
     * PersonPhoneNumber constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->util = resolve(PhoneNumberUtil::class);
        parent::__construct($attributes);
    }

    /**
     * Returns the phone_number as a string.
     *
     * @return string
     * @throws
     */
    public function __toString()
    {
        if ($this->country_code === 'NL') {
            return $this->format(PhoneNumberFormat::NATIONAL);
        } else {
            return $this->format(PhoneNumberFormat::INTERNATIONAL);
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the phone number in the given phoneNumberFormat.
     *
     * @param $phoneNumberFormat
     * @return string
     */
    public function format($phoneNumberFormat) {
        return $this->util->format($this->phone_number, $phoneNumberFormat);
    }

    /**
     * Returns the phone-number in a format that can be dialed form the given country.
     *
     * @param string $country_code
     * @return string
     */
    public function formatFor($country_code = 'NL') {
        return $this->util->formatOutOfCountryCallingNumber($this->phone_number, $country_code);
    }

    /**
     * Returns the phone-number in a format such it can be dialed from a mobile number form the given country.
     *
     * @param string $country_code
     * @param bool $numFormatting
     * @return string
     */
    public function formatMobile($country_code = 'NL', $numFormatting = true) {
        return $this->util->formatNumberForMobileDialing($this->phone_number, $country_code, $numFormatting);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the phone_number as a PhoneNumber
     *
     * @param $value
     * @return PhoneNumber
     * @throws
     */
    public function getPhoneNumberAttribute($value) {
        return $this->util->parse($value, $this->country_code);
    }

    /**
     * Returns the number type of this PhoneNumber
     *
     * @return int
     */
    public function getNumberTypeAttribute() {
        return $this->util->getNumberType($this->phone_number);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM MUTATORS ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Saves the phone_number in the right format.
     *
     * @param $value
     * @throws
     */
    public function setPhoneNumberAttribute($value) {
        if(is_string($value)) {
            $value = $this->util->parse($value, $this->country_code);
        }

        if($value instanceof PhoneNumber) {
            $this->attributes['phone_number'] = $this->util->format($value, PhoneNumberFormat::E164);
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SEARCHABLE CONFIG ---------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'remarks' => $this->remarks,
            'name' => $this->person->name_full,
        ];
    }
}
