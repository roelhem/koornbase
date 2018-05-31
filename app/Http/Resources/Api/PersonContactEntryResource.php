<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 16:40
 */

namespace App\Http\Resources\Api;


class PersonContactEntryResource extends Resource
{

    public function toArray($request)
    {
        return parent::toArray($request) + [
                'index' => $this->index,
                'label' => $this->label,
                'options' => $this->getOptions($request),
            ];
    }

    public function tailArray($request) {
        return [
            'person' => new PersonResource($this->whenLoaded('person')),
        ] + parent::tailArray($request);
    }

}