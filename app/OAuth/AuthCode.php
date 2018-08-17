<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:12
 */

namespace App\OAuth;

use App\User;
use Carbon\Carbon;
use Laravel\Passport\AuthCode as PassportAuthCode;

/**
 * Class AuthCode
 *
 * @package App\OAuth
 * @property string $id
 * @property int $user_id
 * @property int $client_id
 * @property boolean $revoked
 * @property-read Carbon|null $expires_at
 * @property-read boolean $expired
 * @property-read boolean $is_valid
 * @property-read \App\OAuth\Client $client
 * @property-read \App\User $user
 * @mixin \Eloquent
 */
class AuthCode extends PassportAuthCode
{


    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function expired($at = null) {
        $at = \Parse::date($at, true);

        if($this->expires_at < $at) {
            return true;
        }

        return false;
    }

    public function getExpiredAttribute() {
        return $this->expired();
    }

    public function isValid($at = null) {
        return !$this->revoked && !$this->expired($at);
    }

    public function getIsValidAttribute() {
        return $this->isValid();
    }

}