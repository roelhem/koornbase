<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Traits\HasStamps;
use App\KoornbeursCard;
use Illuminate\Http\Resources\Json\JsonResource;

class KoornbeursCardResource extends JsonResource
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
        /** @var KoornbeursCard $card */
        $card = $this->resource;

        return [
            'id' => $card->id,
            'ref' => $card->ref,
            'version' => $card->version,

            'person_id' => $card->owner_id,
            'person' => new PersonResource($this->whenLoaded('owner')),

            'activated_at' => $card->activated_at,
            'deactivated_at' => $card->deactivated_at,
            'is_active' => $card->is_active,
            'remarks' => $card->remarks,

            $this->getStampFields($request)
        ];
    }
}
