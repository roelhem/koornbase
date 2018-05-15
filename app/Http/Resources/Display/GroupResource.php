<?php

namespace App\Http\Resources\Display;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'name_short' => $this->name_short,
            'description' => $this->description,
            'member_name' => $this->member_name,
            'category' => new GroupCategoryResource($this->whenLoaded('category'))
        ];
    }
}
