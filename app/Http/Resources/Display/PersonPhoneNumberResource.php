<?php

namespace App\Http\Resources\Display;

use Illuminate\Http\Resources\Json\JsonResource;
use Propaganistas\LaravelPhone\PhoneNumber;

class PersonPhoneNumberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'is_primary' => $this->is_primary,
            'for_emergency' => $this->for_emergency,
            'phone_number' => $this->phone_number,
            'formats' => $this->when($this->phone_number instanceof PhoneNumber, function() {
                return [
                    'display' => $this->phone_number->formatForCountry('NL'),
                    'dailing' => $this->phone_number->formatForMobileDialingInCountry('NL'),
                    'international' => $this->phone_number->formatInternational(),
                ];
            }),
            'is_mobile' => $this->is_mobile,
            'remarks' => $this->remarks,
        ];
    }
}
