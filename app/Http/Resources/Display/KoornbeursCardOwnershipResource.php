<?php

namespace App\Http\Resources\Display;

use Illuminate\Http\Resources\Json\JsonResource;

class KoornbeursCardOwnershipResource extends JsonResource
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
            'start' => $this->start,
            'end' => $this->end,
            'remarks' => $this->remarks,
            'person' => new PersonResource($this->whenLoaded('person')),
            'card' => new KoornbeursCardResource($this->whenLoaded('card')),
        ];
    }
}
