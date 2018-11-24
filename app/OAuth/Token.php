<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:14
 */

namespace App\OAuth;

use App\Enums\OAuthScope;
use Carbon\Carbon;
use Laravel\Passport\Token as PassportToken;

/**
 * Class Token
 *
 * @package App\OAuth
 * @property string $id
 * @property int $user_id
 * @property int $client_id
 * @property string $name
 * @property boolean $revoked
 * @property array $scopes
 * @property-read OAuthScope[] $scope_objects
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read Carbon|null $expires_at
 * @property-read boolean $expired
 * @property-read boolean $is_valid
 * @property-read \App\OAuth\Client $client
 * @property-read \App\User $user
 * @mixin \Eloquent
 */
class Token extends PassportToken
{

    protected $dates = ['created_at','updated_at','expires_at'];

    /**
     * Attribute that contains the OAuthScope objects of this token.
     *
     * @return OAuthScope[]
     */
    public function getScopeObjectsAttribute() {
        return array_map(function($scope) {
            return OAuthScope::byName($scope);
        }, $this->scopes);
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