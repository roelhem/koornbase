<?php

namespace App\Http\Resources\Api;



class RoleResource extends Resource
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

                'name' => $this->name,
                'description' => $this->description

            ] + $this->tailArray($request);
    }
}
