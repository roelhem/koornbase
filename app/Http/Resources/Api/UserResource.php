<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Traits\HasStamps;
use App\Http\Resources\Api\Types\AvatarResource;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'name_display' => $user->name_display,
            'name_short' => $user->name_short,
            'avatar' => new AvatarResource($user->avatar),
            'email' => $user->email,

            'person_id' => $user->person_id,
            'person' => new PersonResource($this->whenLoaded('person')),

            'accounts' => UserAccountResource::collection($this->whenLoaded('accounts')),
            'facebookAccount' => new UserAccountResource($this->whenLoaded('facebookAccount')),
            'githubAccount' => new UserAccountResource($this->whenLoaded('githubAccount')),
            'googleAccount' => new UserAccountResource($this->whenLoaded('googleAccount')),
            'twitterAccount' => new UserAccountResource($this->whenLoaded('twitterAccount')),

            $this->getStampFields($request),
        ];
    }
}
