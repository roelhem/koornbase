<?php

namespace App\Http\Resources\Api;

use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;

class PersonPhoneNumberResource extends PersonContactEntryResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request) + [
                'country' => $this->country,
                'country_code' => $this->country_code,
                'nl_fixed' => $this->resource->formatFor(),
                'nl_mobile' => $this->resource->formatMobile(),
                'e164' => $this->format(PhoneNumberFormat::E164),
                'type' => $this->number_type,

                'person' => new PersonResource($this->whenLoaded('person')),

            ] + $this->tailArray($request);
    }

    /**
     * Gives the name of the type of this phone-number.
     *
     * @return string
     */
    public function fieldTypeName() {
        return PhoneNumberType::values()[$this->number_type];
    }

    /**
     * Optional field that gives a description form where the phone-number is.
     *
     * @return mixed
     */
    public function fieldLocation() {
        return resolve(PhoneNumberOfflineGeocoder::class)->getDescriptionForNumber($this->phone_number, 'nl_NL');
    }

    /**
     * Optional field that shows the phone number in rfc3966 format for in the use of hyperlinks.
     *
     * @return mixed
     */
    public function fieldRfc3966() {
        return $this->format(PhoneNumberFormat::RFC3966);
    }

    /**
     * Optional field that shows the phone-number in a format for national calls.
     *
     * @return mixed
     */
    public function fieldNational() {
        return $this->format(PhoneNumberFormat::NATIONAL);
    }

    /**
     * Optional field that shows the phone-number in a format for international calls.
     *
     * @return mixed
     */
    public function fieldInternational() {
        return $this->format(PhoneNumberFormat::INTERNATIONAL);
    }


}
