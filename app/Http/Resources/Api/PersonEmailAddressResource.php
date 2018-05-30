<?php

namespace App\Http\Resources\Api;

class PersonEmailAddressResource extends PersonContactEntryResource
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
            'email_address' => $this->email_address,
        ] + $this->tailArray($request);
    }
}
