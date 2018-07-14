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
                'administrative_area' => $this->administrative_area,
                'locality' => $this->locality,
                'dependent_locality' => $this->dependent_locality,
                'postal_code' => $this->postal_code,
                'sorting_code' => $this->sorting_code,
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'organisation' => $this->organisation,
                'locale' => $this->locale
        ] + $this->tailArray($request);
    }

    public function fieldFormatted() {
        return $this->resource->format();
    }

    public function fieldHtmlFormatted() {
        return $this->resource->format(['html' => true, 'html_attributes' => ['translate' => 'no', 'class' => 'generated_address_html']]);
    }

    public function fieldPostalLabel() {
        return $this->resource->postalLabel();
    }

    public function fieldAddressFormat() {
        return new AddressFormatResource($this->addressFormat);
    }
}
