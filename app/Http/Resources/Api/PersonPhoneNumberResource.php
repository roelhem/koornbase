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
                'type' => PhoneNumberType::values()[$this->number_type],
                'location' => resolve(PhoneNumberOfflineGeocoder::class)->getDescriptionForNumber($this->phone_number, 'nl_NL'),
                'country' => $this->country,
                'country_code' => $this->country_code,
                'e164' => $this->format(PhoneNumberFormat::E164),
                'rfc3966' => $this->format(PhoneNumberFormat::RFC3966),
                'national' => $this->format(PhoneNumberFormat::NATIONAL),
                'international' => $this->format(PhoneNumberFormat::INTERNATIONAL),
            ] + $this->tailArray($request);
    }
}
