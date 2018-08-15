<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Traits\HasStamps;
use App\Membership;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipResource extends JsonResource
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
        /** @var Membership $membership */
        $membership = $this->resource;

        return [
            'id' => $membership->id,
            'person_id' => $membership->person_id,
            'person' => new PersonResource($this->whenLoaded('person')),

            'application' => $membership->application,
            'start' => $membership->start,
            'end' => $membership->end,

            'status' => $membership->status->getName(),
            'status_since' => $membership->status_at,

            'remarks' => $membership->remarks,

            $this->getStampFields($request),
        ];
    }
}
