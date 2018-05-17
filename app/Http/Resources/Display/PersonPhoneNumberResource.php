<?php

namespace App\Http\Resources\Display;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'is_mobile' => $this->is_mobile,
            'remarks' => $this->remarks,
        ];
    }
}
