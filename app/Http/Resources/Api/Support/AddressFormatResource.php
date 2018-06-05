<?php

namespace App\Http\Resources\Api\Support;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressFormatResource extends JsonResource
{

    use hasGetterMethods;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     * @throws
     */
    public function toArray($request)
    {
        return $this->getGetterMethods();
    }


}
