<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 18:50
 */

namespace App\Traits\PersonContactEntry;


trait AddressingAttributeMapping
{
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
    public function getGivenName()
    {
        if($this->person === null) {
            return null;
        }
        return $this->person->name_first;
    }

    /**
     * @inheritdoc
     */
    public function getAdditionalName()
    {
        if($this->person === null) {
            return null;
        }
        return $this->person->name_prefix;
    }

    /**
     * @inheritdoc
     */
    public function getFamilyName()
    {
        if($this->person === null) {
            return null;
        }
        return $this->person->name_last;
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
}