<?php

namespace App;

use App\Contracts\OwnedByPerson;
use App\Traits\HasRemarks;
use App\Traits\BelongsToPerson;
use App\Traits\PersonContactEntry\AddressingAttributeMapping;
use App\Traits\PersonContactEntry\HasContactOptions;
use App\Traits\PersonContactEntry\HasCountryCode;
use App\Traits\PersonContactEntry\OrderableWithIndex;
use Carbon\Carbon;
use CommerceGuys\Addressing\AddressFormat\AddressFormat;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepositoryInterface;
use CommerceGuys\Addressing\AddressInterface;
use CommerceGuys\Addressing\Formatter\FormatterInterface;
use CommerceGuys\Addressing\Formatter\PostalLabelFormatterInterface;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

/**
 * Class PersonAddress
 * @package App
 *
 * @property integer|null $id
 * @property string|null $label
 *
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
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 *
 * @property-read AddressFormat $addressFormat
 */
class PersonAddress extends Model implements AddressInterface, OwnedByPerson
{

    use Userstamps;
    use Filterable;

    use HasRemarks, BelongsToPerson;

    use HasContactOptions, OrderableWithIndex, HasCountryCode, AddressingAttributeMapping;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'person_addresses';

    protected $fillable = ['label',
                           'country_code','administrative_area','locality','dependent_locality','postal_code',
                           'sorting_code','address_line_1','address_line_2','organisation','locale',
                           'options','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- FORMATTING METHODS --------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @param array $options
     * @return string
     */
    public function format($options = []) {
        $formatter = resolve(FormatterInterface::class);
        return $formatter->format($this, $options);
    }

    /**
     * @param array $options
     * @return string
     */
    public function postalLabel($options = []) {
        $formatter = resolve(PostalLabelFormatterInterface::class);
        return $formatter->format($this, $options);
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The AddressFormat of this PersonAddress (based on the country_code.)
     *
     * @return AddressFormat
     */
    public function getAddressFormatAttribute() {
        $addressFormatRepository = resolve(AddressFormatRepositoryInterface::class);
        return $addressFormatRepository->get($this->country_code);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- BOOT AND STATIC ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The boot settings
     */
    public static function boot()
    {
        static::saving(function(PersonAddress $personAddress) {
            $addressFormat = $personAddress->addressFormat;
            $usedFields = $addressFormat->getUsedFields();
            foreach (self::addressFieldAttributeNames() as $attributeName) {
                if($attributeName === 'organisation') {
                    $fieldName = 'organization';
                } else {
                    $fieldName = camel_case($attributeName);
                }

                if(!in_array($fieldName, $usedFields)) {
                    $personAddress->$attributeName = null;
                }
            }
        });

        parent::boot();
    }

    /**
     * Returns the names of the attributes that store the address values.
     *
     * @return array
     */
    public static function addressFieldAttributeNames() {
        return [
            'administrative_area','locality','dependent_locality','postal_code','sorting_code',
            'address_line_1','address_line_2','organisation'
        ];
    }

}
