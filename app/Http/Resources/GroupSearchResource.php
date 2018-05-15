<?php

namespace App\Http\Resources;

use App\Group;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $group = $this->resource;

        if($group instanceof Group) {
            return [
                'id' => $group->id,
                'slug' => $group->slug,
                'name' => $group->name,
                'name_short' => $group->name_short,
                'description' => $group->description,
                'member_name' => $group->member_name
            ];
        }

        return parent::toArray($request);
    }
}
