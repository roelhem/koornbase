<?php

namespace App\Http\Resources\Api;

use App\GroupCategory;
use App\Http\Resources\Api\Traits\HasStamps;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupCategoryResource extends JsonResource
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
        /** @var GroupCategory $category */
        $category = $this->resource;

        return [
            'id' => $category->id,
            'slug' => $category->slug,
            'name' => $category->name,
            'name_short' => $category->name_short,
            'description' => $category->description,
            'style' => $category->style,
            'groups' => GroupResource::collection($this->whenLoaded('groups')),
            'is_required' => $category->is_required,

            $this->getStampFields($request),
        ];
    }
}
