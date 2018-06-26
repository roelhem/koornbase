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
                'name_first' => $this->name_first,
                'name_middle' => $this->name_middle,
                'name_prefix' => $this->name_prefix,
                'name_last' => $this->name_last,
                'name_initials' => $this->name_initials,
                'name_nickname' => $this->name_nickname,
                'birth_date' => $this->formatDate($this->birth_date, $request),

                'addresses' => PersonAddressResource::collection($this->whenLoaded('addresses')),
                'emailAddresses' => PersonEmailAddressResource::collection($this->whenLoaded('emailAddresses')),
                'phoneNumbers' => PersonPhoneNumberResource::collection($this->whenLoaded('phoneNumbers')),

                'users' => UserResource::collection($this->whenLoaded('users')),
                'groups' => GroupResource::collection($this->whenLoaded('groups')),
                'certificates' => CertificateResource::collection($this->whenLoaded('certificates')),
                'cards' => KoornbeursCardResource::collection($this->whenLoaded('cards'))

            ] + $this->tailArray($request);
    }


    public function fieldNameFormal($request) {
        return $this->name_formal;
    }

    public function fieldNameFull($request) {
        return $this->name_full;
    }

    public function fieldAge($request) {
        return $this->age;
    }

    public function fieldAvatar($request) {
        return $this->avatar;
    }

    public function fieldMembershipStatus($request) {
        if($this->membership_status === null) {
            return null;
        }

        $res = [
            'status' => $this->membership_status,
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
