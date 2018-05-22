<?php

namespace App\Http\Resources\Display;

use Illuminate\Http\Resources\Json\JsonResource;

class KoornbeursCardResource extends JsonResource
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
            'activated_at' => $this->activaded_at,
            'deactivated_at' => $this->deactivated_at,
            'version' => $this->version,
            'remarks' => $this->remarks,
            'ownerships' => KoornbeursCardOwnershipResource::collection($this->whenLoaded('ownerships')),
        ];
}
}
