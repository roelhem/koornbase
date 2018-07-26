<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:14
 */

namespace App\OAuth;

use Carbon\Carbon;
use Laravel\Passport\Token as PassportToken;

/**
 * Class Token
 *
 * @package App\OAuth
 *
 * @property string $id
 * @property int $user_id
 * @property int $client_id
 * @property string $name
 * @property boolean $revoked
 *
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read Carbon|null $expires_at
 *
 * @property-read boolean $expired
 * @property-read boolean $is_valid
 */
class Token extends PassportToken
{


    public function expired($at = null) {
        if(!($at instanceof Carbon)) {
            $at = Carbon::parse($at);
        }

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