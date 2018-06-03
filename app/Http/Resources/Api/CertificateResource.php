<?php

namespace App\Http\Resources\Api;

class CertificateResource extends Resource
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
                'passed' => $this->passed,
                'examination_at' => $this->formatdate($this->examination_at, $request),
                'valid_at' => $this->formatdate($this->valid_at, $request),
                'expired_at' => $this->formatdate($this->expired_at, $request),

                'is_valid' => $this->is_valid,

                'person' => new PersonResource($this->whenLoaded('person')),
                'category' => new CertificateCategoryResource($this->whenLoaded('category'))
            ] + $this->tailArray($request);
    }
}
