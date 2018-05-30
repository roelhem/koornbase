<?php

namespace App\Http\Resources\Display;

use Illuminate\Http\Resources\Json\JsonResource;

class MembershipResource extends JsonResource
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
            'application' => $this->application,
            'start' => $this->start,
            'end' => $this->end,
            'remarks' => $this->remarks
        ];
    }
}
