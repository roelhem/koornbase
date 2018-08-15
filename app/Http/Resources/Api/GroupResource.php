<?php

namespace App\Http\Resources\Api;

use App\Group;
use App\Http\Resources\Api\Traits\HasStamps;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
        /** @var Group $group */
        $group = $this->resource;

        return [
            'id' => $group->id,
            'slug' => $group->slug,
            'category_id' => $group->category_id,
            'category' => GroupCategoryResource::collection($this->whenLoaded('category')),

            'name' => $group->name,
            'name_short' => $group->name_short,
            'description' => $group->description,
            'member_name' => $group->member_name,
            'emailAddresses' => GroupEmailAddressResource::collection($this->whenLoaded('emailAddresses')),
            'persons' => PersonResource::collection($this->whenLoaded('persons')),
            'is_required' => $group->is_required,

            $this->getStampFields($request),
        ];
    }
}
