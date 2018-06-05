<?php

namespace App\Http\Resources\Api;


class UserAccountResource extends Resource
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
                'provider' => $this->provider,
                'ref_id' => $this->ref_id,
                'nickname' => $this->nickname,
                'name' => $this->name,
                'email' => $this->email,
                'avatar' => $this->avatar
            ] + $this->tailArray($request);
    }

    public function fieldUserJson($request) {
        return $this->user_json;
    }
}
