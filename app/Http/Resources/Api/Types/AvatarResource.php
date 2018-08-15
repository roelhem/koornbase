<?php

namespace App\Http\Resources\Api\Types;

use App\Types\AvatarType;
use Illuminate\Http\Resources\Json\JsonResource;

class AvatarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var AvatarType $avatar */
        $avatar = $this->resource;

        return [
            'image' => $avatar->image,
            'letters' => $avatar->letters,
        ];
    }
}
