<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Traits\HasStamps;
use App\Http\Resources\Api\Types\AvatarResource;
use App\Person;
use App\PersonEmailAddress;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
        /** @var Person $person */
        $person = $this->resource;

        return [
            'id' => $person->id,
            'name' => $person->name,
            'name_short' => $person->name_short,
            'name_first' => $person->name_first,
            'name_middle' => $person->name_middle,
            'name_prefix' => $person->name_prefix,
            'name_last' => $person->name_last,
            'name_initials' => $person->name_initials,
            'name_nickname' => $person->name_nickname,
            'avatar' => new AvatarResource($person->avatar),
            'birth_date' => $person->birth_date,
            'age' => $person->age,

            'membership_status' => $person->membership_status->getName(),
            'membership_status_since' => $person->membership_status_since,
            'memberships' => MembershipResource::collection($this->whenLoaded('memberships')),

            'groups' => GroupResource::collection($this->whenLoaded('groups')),
            'certificates' => CertificateResource::collection($this->whenLoaded('certificates')),
            'users' => UserResource::collection($this->whenLoaded('users')),

            'cards' => KoornbeursCardResource::collection($this->whenLoaded('cards')),
            'activeCards' => KoornbeursCardResource::collection($this->whenLoaded('activeCards')),

            'addresses' => PersonAddressResource::collection($this->whenLoaded('addresses')),
            'address' => new PersonAddressResource($this->whenLoaded('address')),
            'emailAddresses' => PersonEmailAddressResource::collection($this->whenLoaded('emailAddresses')),
            'emailAddress' => new PersonEmailAddressResource($this->whenLoaded('emailAddress')),
            'phoneNumbers' => PersonPhoneNumberResource::collection($this->whenLoaded('phoneNumbers')),
            'phoneNumber' => new PersonPhoneNumberResource($this->whenLoaded('phoneNumber')),

            'remarks' => $person->remarks,

            $this->getStampFields($request),
        ];
    }
}
