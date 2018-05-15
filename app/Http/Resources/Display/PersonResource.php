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
            'name' => [
                'full' => $this->name,
                'short' => $this->name_short,
                'initials' => $this->name_initials,
                'first' => $this->name_first,
                'middle' => $this->name_middle,
                'prefix' => $this->name_prefix,
                'last' => $this->name_last,
                'nickname' => $this->nickname,
            ],
            'birth_date' => $this->birth_date->toDateString(),
            'remarks' => $this->remarks,
            'avatar' => [
                'letters' => $this->avatar_letters,
                'image' => $this->avatar
            ],
            'membership_status' => $this->membership_status,

            'groupMemberships' => GroupMembershipResource::collection($this->whenLoaded('groupMemberships')),
        ];
    }
}
