<?php

namespace App\Http\Resources\Api;

use App\Certificate;
use App\Http\Resources\Api\Traits\HasStamps;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
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
        /** @var Certificate $certificate */
        $certificate = $this->resource;

        return [
            'id' => $certificate->id,
            'person_id' => $certificate->person_id,
            'person' => new PersonResource($this->whenLoaded('person')),
            'category_id' => $certificate->category_id,
            'category' => new CertificateCategoryResource($this->whenLoaded('category')),

            'examination_at' => $certificate->examination_at,
            'passed' => $certificate->passed,
            'valid_at' => $certificate->valid_at,
            'expired_at' => $certificate->expired_at,
            'is_valid' => $certificate->is_valid,

            'remarks' => $certificate->remarks,

            $this->getStampFields($request),
        ];
    }
}
