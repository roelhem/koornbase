<?php

namespace App\Http\Resources\Api;


use App\Enums\MembershipStatus;

class MembershipResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request) + [
                'application' => $this->formatDate($this->application, $request),
                'start' => $this->formatDate($this->start, $request),
                'end' => $this->formatDate($this->end, $request),
                'status' =>  $this->status,

                'person' => new PersonResource($this->whenLoaded('person')),

            ] + $this->tailArray($request);
    }

    public function fieldStatusName() {
        return MembershipStatus::getDescription($this->status);
    }

    public function fieldStatusLabel() {
        return MembershipStatus::getLabel($this->status);
    }
}
