<?php

namespace App\Http\Resources\Api;

class UserResource extends Resource
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
                'email' => $this->email,

                'person' => new PersonResource($this->whenLoaded('person')),
                'accounts' => UserAccountResource::collection($this->whenLoaded('accounts')),
                'facebookAccount' => new UserAccountResource($this->whenLoaded('facebookAccount')),
                'githubAccount' => new UserAccountResource($this->whenLoaded('githubAccount')),
                'googleAccount' => new UserAccountResource($this->whenLoaded('googleAccount')),
                'twitterAccount' => new UserAccountResource($this->whenLoaded('twitterAccount')),
            ] + $this->tailArray($request);
    }

    public function fieldNameDisplay($request) {
        return $this->name_display;
    }

    public function fieldNameShort($request) {
        return $this->name_short;
    }

    public function fieldAvatar($request) {
        return $this->avatar;
    }
}
