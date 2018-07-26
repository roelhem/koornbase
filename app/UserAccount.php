<?php

namespace App;

use App\Enums\OAuthProvider;
use App\Services\Sorters\Traits\Sortable;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Laravel\Socialite\Contracts\User as OAuthUserContract;
use Laravel\Socialite\AbstractUser as AbstractOAuthUser;
use Laravel\Socialite\Two\User as OAuthUser;

/**
 * Class UserAccount
 * @package App
 *
 * @property integer $id
 * @property integer $user_id
 * @property OAuthProvider $provider
 * @property string $token
 * @property string|null $refresh_token
 * @property integer|null $expires_in
 *
 * @property string|null $ref_id
 * @property string|null $nickname
 * @property string|null $name
 * @property string|null $email
 * @property string|null $avatar
 *
 * @property array|null $user_json
 *
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 *
 * @property-read User $user
 */
class UserAccount extends Model
{
    use Userstamps;
    use Filterable, Sortable;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'user_accounts';

    protected $casts = ['user_json' => 'array'];

    protected $fillable = ['user_id', 'provider', 'oauthUser'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @return OAuthUser
     */
    public function getOAuthUserAttribute() {
        $result = new OAuthUser();

        $result->id = $this->ref_id;
        $result->nickname = $this->nickname;
        $result->name = $this->name;
        $result->email = $this->email;
        $result->avatar = $this->avatar;
        $result->user = $this->user_json;

        $result->token = $this->token;
        $result->refreshToken = $this->expires_in;
        $result->expiresIn = $this->expires_in;
        $result->refreshToken = $this->refresh_token;

        return $result;
    }

    /**
     * Converts the string-value of the provider to an instance of OAuthProvider.
     *
     * @param string $value
     * @return OAuthProvider
     */
    public function getProviderAttribute($value) {
        return OAuthProvider::get($value);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM MUTATORS ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    public function setOAuthUserAttribute($user) {

        if($user instanceof OAuthUserContract) {

            $this->ref_id = $user->getId();
            $this->nickname = $user->getNickname();
            $this->name = $user->getName();
            $this->email = $user->getEmail();
            $this->avatar = $user->getAvatar();

            if ($user instanceof AbstractOAuthUser) {
                $this->user_json = $user->getRaw();
            }

            if ($user instanceof OAuthUser) {
                $this->token = $user->token;
                $this->refresh_token = $user->refreshToken;
                $this->expires_in = $user->expiresIn;
            }
        }
    }


    /**
     * Ensures that the provider can be set by an OAuthProvider instance and that it will always be an element
     * of OAuthProvider.
     *
     * @param OAuthProvider|string $newValue
     */
    public function setProviderAttribute($newValue)
    {
        $this->attributes['provider'] = OAuthProvider::get($newValue)->value;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * A scope that only gives the accounts of the given provider
     *
     * @param Builder $query
     * @param string $name
     * @return Builder
     */
    public function scopeProvider($query, $name) {
        return $query->where('provider','=',$name);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the user where this UserAccount belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
