<?php

namespace App\Http\Resources\Api\Support;

use CommerceGuys\Addressing\AddressFormat\AddressFormatRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{

    use HasGetterMethods;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     * @throws
     */
    public function toArray($request)
    {
        $addressFormatRepository = resolve(AddressFormatRepositoryInterface::class);
        $addressFormat = $addressFormatRepository->get($this->getCountryCode());

        return $this->getGetterMethods() + [
                'addressFormat' => new AddressFormatResource($addressFormat),
            ];
    }
}
