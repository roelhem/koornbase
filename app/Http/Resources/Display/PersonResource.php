<?php

namespace App\Http\Resources\Display;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'name' => $this->name_array,
            'birth_date' => $this->birth_date,
            'remarks' => $this->remarks,
            'avatar' => [
                'letters' => $this->avatar_letters,
                'image' => $this->avatar,
            ],
            'membership_status' => $this->membership_status,
            'membership_status_since' => $this->membership_status_since,

            'groupMemberships' => GroupMembershipResource::collection($this->whenLoaded('groupMemberships')),
            'emailAddresses' => PersonEmailAddressResource::collection($this->whenLoaded('emailAddresses')),
            'phoneNumbers' => PersonPhoneNumberResource::collection($this->whenLoaded('phoneNumbers')),
            'cardOwnership' => new KoornbeursCardOwnershipResource($this->whenLoaded('cardOwnership')),
        ];
    }
}
