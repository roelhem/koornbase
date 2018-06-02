<?php

namespace App\Http\Resources\Api;

class GroupResource extends Resource
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
                'slug' => $this->slug,
                'name' => $this->name,
                'name_short' => $this->name_short,
                'description' => $this->description,
                'member_name' => $this->member_name,
                'is_required' => $this->when($this->is_required, true),

                'category' => new GroupCategoryResource($this->whenLoaded('category')),
                'persons' => PersonResource::collection($this->whenLoaded('persons')),
                'emailAddresses' => GroupEmailAddressResource::collection($this->whenLoaded('emailAddresses')),

            ] + $this->tailArray($request);
    }
}
