<?php

namespace App\Http\Resources;

use App\GroupCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupCategorySearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $category = $this->resource;

        if($category instanceof GroupCategory) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'name_short' => $category->name_short,
                'description' => $category->description,
                'groups' => GroupSearchResource::collection($category->groups)
            ];
        }

        return parent::toArray($request);
    }
}
