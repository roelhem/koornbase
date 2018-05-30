<?php

namespace App\Http\Resources\Api;

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
            'is_mobile' => $this->is_mobile,
            'phone_number' => $this->phone_number,
            'country_code' => $this->country_code,
                'country' => $this->country,
        ] + $this->tailArray($request);
    }
}
