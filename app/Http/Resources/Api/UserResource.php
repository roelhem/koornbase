<?php

namespace App\Http\Resources\Api;

class UserResource extends Resource
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
            'name' => $this->name,
            'email' => $this->email,

            'person' => new PersonResource($this->whenLoaded('person')),
        ] + $this->tailArray($request);
    }
}
