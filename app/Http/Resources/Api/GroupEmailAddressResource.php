<?php

namespace App\Http\Resources\Api;

use App\GroupEmailAddress;
use App\Http\Resources\Api\Traits\HasStamps;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupEmailAddressResource extends JsonResource
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

        /** @var GroupEmailAddress $adres */
        $adres = $this->resource;

        return [
            'id' => $adres->id,
            'email_address' => $adres->email_address,
            'group_id' => $adres->group_id,
            'group' => new GroupResource($this->whenLoaded('group')),
            'remarks' => $adres->remarks,

            $this->getStampFields($request),
        ];
    }
}
