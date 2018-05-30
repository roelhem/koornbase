<?php

namespace App;

use Carbon\Carbon;
use CommerceGuys\Addressing\Model\AddressInterface;
use CommerceGuys\Addressing\Repository\CountryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Wildside\Userstamps\Userstamps;

/**
 * Class PersonAddress
 * @package App
 *
 * @property integer|null $id
 * @property integer|null $person_id
 * @property string|null $label
 * @property boolean|null $is_primary
 * @property boolean|null $for_emergency
 *
 * @property string|null $country_code
 * @property string|null $administrative_area
 * @property string|null $locality
 * @property string|null $dependent_locality
 * @property string|null $postal_code
 * @property string|null $sorting_code
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $organisation
 * @property string|null $locale
 *
 * @property string|null $remarks
 *
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 *
 * @property-read Person $person
 * @property-read string|null $country
 */
class PersonAddress extends Model implements AddressInterface
{

    use Userstamps;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'person_addresses';

    protected $fillable = ['label','is_primary','for_emergency',
                           'country_code','administrative_area','locality','dependent_locality','postal_code',
                           'sorting_code','address_line_1','address_line_2','organisation','locale',
                           'remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INTERFACE CONFORMATION: AddressInterface ----------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @inheritdoc
     */
    public function getAdministrativeArea()
    {
        return $this->administrative_area;
    }

    /**
     * @inheritdoc
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @inheritdoc
     */
    public function getDependentLocality()
    {
        return $this->dependent_locality;
    }

    /**
     * @inheritdoc
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @inheritdoc
     */
    public function getSortingCode()
    {
        return $this->sorting_code;
    }

    /**
     * @inheritdoc
     */
    public function getAddressLine1()
    {
        return $this->address_line_1;
    }

    /**
     * @inheritdoc
     */
    public function getAddressLine2()
    {
       return $this->address_line_2;
    }

    /**
     * @inheritdoc
     */
    public function getRecipient()
    {
        return $this->person->name;
    }

    /**
     * @inheritdoc
     */
    public function getOrganization()
    {
        return $this->organisation;
    }

    /**
     * @inheritdoc
     */
    public function getLocale()
    {
        return $this->locale;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the name of the country of this Address.
     *
     * @return string|null
     */
    public function getCountryAttribute() {

        $code = $this->getCountryCode();
        $list = app(CountryRepositoryInterface::class)->getList('NL');

        return array_get($list, $code);
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
     * Gives the Person where this PersonAddress belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
