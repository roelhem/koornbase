<?php

namespace App\Http\Resources\Api;


use App\Http\Resources\Api\Support\AddressFormatResource;

class PersonAddressResource extends PersonContactEntryResource
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
            'country_code' => $this->country_code,
                'country' => $this->country,
            'administrative_area' => $this->when($this->administrative_area !== null, $this->administrative_area),
            'locality' => $this->when($this->locality !== null, $this->locality),
            'dependent_locality' => $this->when($this->dependent_locality !== null, $this->dependent_locality),
            'postal_code' => $this->when($this->postal_code !== null, $this->postal_code),
            'sorting_code' => $this->when($this->sorting_code !== null, $this->sorting_code),
            'address_line_1' => $this->when($this->address_line_1 !== null, $this->address_line_1),
            'address_line_2' => $this->when($this->address_line_2 !== null, $this->address_line_2),
            'organisation' => $this->when($this->organisation !== null, $this->organisation),
            'locale' => $this->when($this->locale !== 'und', $this->locale)
        ] + $this->tailArray($request);
    }

    public function fieldFormatted() {
        return $this->resource->format();
    }

    public function fieldPostalLabel() {
        return $this->resource->postalLabel();
    }

    public function fieldAddressFormat() {
        return new AddressFormatResource($this->addressFormat);
    }
}
