<?php

namespace App\Http\Resources\Select;

use Illuminate\Http\Resources\Json\JsonResource;

class TreeResource extends JsonResource
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
            'label' => $this->getLabel(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription()
        ];
    }

    /**
     * Returns the value that can be used as the label of the select resource
     *
     * @return string
     */
    protected function getLabel() {
        return $this->name_short ?? $this->name ?? $this->id;
    }

    protected function getTitle() {
        return $this->name ?? $this->short_name ?? $this->id;
    }

    protected function getDescription() {
        $description = $this->description;

        $showDescription = boolval($description);

        return $this->when($showDescription, $description);
    }
}
