<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Traits\HasStamps;
use App\PersonAddress;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonAddressResource extends JsonResource
{
    use HasStamps;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var PersonAddress $address */
        $address = $this->resource;

        return [
            'id' => $address->id,
            'label' => $address->label,
            'index' => $address->index,
            'person_id' => $address->person_id,
            'person' => new PersonResource($this->whenLoaded('person')),

            'country_code' => $address->country_code,
            'administrative_area' => $address->administrative_area,
            'locality' => $address->locality,
            'dependent_locality' => $address->dependent_locality,
            'postal_code' => $address->postal_code,
            'sorting_code' => $address->sorting_code,
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'organisation' => $address->organisation,
            'locale' => $address->locale,

            'remarks' => $address->remarks,

            $this->getStampFields($request),
        ];
    }
}
