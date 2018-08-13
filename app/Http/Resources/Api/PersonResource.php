<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;

class PersonResource extends Resource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request) + [
                'name' => $this->name,
                'name_short' => $this->name_short,
                'name_formal' => $this->name_formal,
                'name_full' => $this->name_full,
                'name_first' => $this->name_first,
                'name_middle' => $this->name_middle,
                'name_prefix' => $this->name_prefix,
                'name_last' => $this->name_last,
                'name_initials' => $this->name_initials,
                'name_nickname' => $this->name_nickname,
                'avatar' => $this->avatar,
                'birth_date' => $this->formatDate($this->birth_date, $request),
                'age' => $this->age,

                'address' => new PersonAddressResource($this->whenLoaded('address')),
                'addresses' => PersonAddressResource::collection($this->whenLoaded('addresses')),
                'emailAddress' => new PersonEmailAddressResource($this->whenLoaded('emailAddress')),
                'emailAddresses' => PersonEmailAddressResource::collection($this->whenLoaded('emailAddresses')),
                'phoneNumber' => new PersonPhoneNumberResource($this->whenLoaded('phoneNumber')),
                'phoneNumbers' => PersonPhoneNumberResource::collection($this->whenLoaded('phoneNumbers')),

                'users' => UserResource::collection($this->whenLoaded('users')),
                'groups' => GroupResource::collection($this->whenLoaded('groups')),
                'memberships' => MembershipResource::collection($this->whenLoaded('memberships')),
                'certificates' => CertificateResource::collection($this->whenLoaded('certificates')),
                'cards' => KoornbeursCardResource::collection($this->whenLoaded('cards')),
                'activeCards' => KoornbeursCardResource::collection($this->whenLoaded('activeCards'))

            ] + $this->tailArray($request);
    }

    public function fieldMembershipStatus($request) {
        if($this->membership_status === null) {
            return null;
        }

        $res = [
            'status' => $this->membership_status->value,
            'name' => $this->membership_status->name,
            'title' => $this->membership_status->title,
        ];

        $since = $this->membership_status_since;

        if($since !== null) {
            $res['since'] = $since->format('Y-m-d');
        }

        return $res;

    }
}
