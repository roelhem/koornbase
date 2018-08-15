<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Traits\HasStamps;
use App\Http\Resources\Api\Types\AvatarResource;
use App\Types\AvatarType;
use App\UserAccount;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAccountResource extends JsonResource
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
        /** @var UserAccount $userAccount */
        $userAccount = $this->resource;

        $avatar = new AvatarType();
        $avatar->image = $userAccount->avatar;

        return [
            'id' => $userAccount->id,
            'user_id' => $userAccount->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'provider' => $userAccount->provider->getName(),
            'ref_id' => $userAccount->ref_id,
            'nickname' => $userAccount->nickname,
            'email' => $userAccount->email,
            'avatar' => new AvatarResource($avatar),

            $this->getStampFields($request)
        ];
    }
}
