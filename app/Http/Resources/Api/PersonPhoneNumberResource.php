<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Traits\HasStamps;
use App\PersonPhoneNumber;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonPhoneNumberResource extends JsonResource
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
        /** @var PersonPhoneNumber $number */
        $number = $this->resource;

        return [
            'id' => $number->id,
            'label' => $number->label,
            'index' => $number->index,
            'person_id' => $number->person_id,
            'person' => new PersonResource($this->whenLoaded('person')),

            'country_code' => $number->country_code,
            'phone_number' => $number->phone_number,

            'remarks' => $number->remarks,

            $this->getStampFields($request),
        ];
    }
}
