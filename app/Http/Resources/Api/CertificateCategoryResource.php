<?php

namespace App\Http\Resources\Api;


class CertificateCategoryResource extends Resource
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
                'name_short' => $this->name_short,
                'description' => $this->description,
                'default_expire_years' => $this->default_expire_years,
                'is_required' => $this->is_required,

                'certificates' => CertificateResource::collection($this->whenLoaded('certificates')),
            ] + $this->tailArray($request);
    }
}
