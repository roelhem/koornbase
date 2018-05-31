<?php

namespace App\Http\Resources\Api;

class GroupCategoryResource extends Resource
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
            'style' => $this->style,
            'is_required' => $this->when($this->is_required, true),
            'options' => $this->getOptions($request),
        ] + $this->tailArray($request);
    }
}
