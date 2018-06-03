<?php

namespace App\Http\Resources\Api;

use App\Enums\MembershipStatus;
use App\KoornbeursCard;
use Carbon\Carbon;
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
                'nickname' => $this->nickname,
                'birth_date' => $this->formatedBirthDate(),
                'age' => $this->age,

                $this->membershipStatus($request),

                'addresses' => PersonAddressResource::collection($this->whenLoaded('addresses')),
                'emailAddresses' => PersonEmailAddressResource::collection($this->whenLoaded('emailAddresses')),
                'phoneNumbers' => PersonPhoneNumberResource::collection($this->whenLoaded('phoneNumbers')),

                'users' => UserResource::collection($this->whenLoaded('users')),
                'groups' => GroupResource::collection($this->whenLoaded('groups')),
                'certificates' => CertificateResource::collection($this->whenLoaded('certificates')),
                'cards' => KoornbeursCardResource::collection($this->whenLoaded('cards')),

            ] + $this->tailArray($request);
    }

    protected function formatedBirthDate() {
        if($this->birth_date instanceof Carbon) {
            return $this->birth_date->format('Y-m-d');
        } else {
            return $this->birth_date;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\MissingValue|mixed
     */
    protected function membershipStatus($request) {
        if($this->membership_status === null) {
            return null;
        };

        $res = [
            'status' => $this->membership_status,
            'name' => MembershipStatus::getDescription($this->membership_status),
            'label' => MembershipStatus::getLabel($this->membership_status),
        ];

        $since = $this->membership_status_since;

        if($since !== null) {
            $res['since'] = $since->format('Y-m-d');
        }

        return $this->mergeWhen(true, [
            'membership_status' => $res
        ]);

    }
}
