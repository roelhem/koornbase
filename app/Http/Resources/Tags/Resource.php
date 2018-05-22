<?php

namespace App\Http\Resources\Tags;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
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
            'name' => $this->when($this->name, $this->name),
            'name_short' => $this->when($this->name_short, $this->name_short),
            'description' => $this->when($this->description, $this->description),
            'style' => $this->when($this->style, $this->style),
        ];
    }
}
