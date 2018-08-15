<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Traits\HasStamps;
use App\PersonEmailAddress;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonEmailAddressResource extends JsonResource
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
        /** @var PersonEmailAddress $emailAddress */
        $emailAddress = $this->resource;

        return [
            'id' => $emailAddress->id,
            'label' => $emailAddress->label,
            'index' => $emailAddress->index,
            'person_id' => $emailAddress->person_id,
            'person' => new PersonResource($this->whenLoaded('person')),

            'email_address' => $emailAddress->email_address,

            'remarks' => $emailAddress->remarks,

            $this->getStampFields($request),
        ];
    }
}
