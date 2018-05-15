<?php

namespace App\Http\Resources\Display;

use App\Enums\GroupCategoryStyles;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupCategoryResource extends JsonResource
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
            'name' => $this->name,
            'name_short' => $this->name_short,
            'description' => $this->description,
            'style' => GroupCategoryStyles::getStyle($this->style),
            'options' => $this->options->toArray(),
        ];
    }
}
