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
            'is_required' => $this->is_required,

            'category' => new GroupCategoryResource($this->whenLoaded('category')),
        ] + $this->tailArray($request);
    }
}
