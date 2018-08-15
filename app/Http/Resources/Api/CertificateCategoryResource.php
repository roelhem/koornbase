<?php

namespace App\Http\Resources\Api;

use App\CertificateCategory;
use App\Http\Resources\Api\Traits\HasStamps;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateCategoryResource extends JsonResource
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

        /** @var CertificateCategory $category */
        $category = $this->resource;

        return [
            'id' => $category->id,
            'slug' => $category->slug,
            'name' => $category->name,
            'name_short' => $category->name_short,
            'description' => $category->description,
            'default_expire_years' => $category->default_expire_years,
            'is_required' => $category->is_required,

            'certificates' => CertificateResource::collection($this->whenLoaded('certificates')),

            $this->getStampFields($request),
        ];
    }
}
