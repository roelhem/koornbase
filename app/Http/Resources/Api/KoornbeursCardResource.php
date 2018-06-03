<?php

namespace App\Http\Resources\Api;

class KoornbeursCardResource extends Resource
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
                'ref' => $this->ref,
                'version' => $this->version,
                'activated_at' => $this->activated_at,
                'deactivated_at' => $this->deactivated_at,

                'owner' => new PersonResource($this->whenLoaded('owner')),
            ] + $this->tailArray($request);
    }
}
