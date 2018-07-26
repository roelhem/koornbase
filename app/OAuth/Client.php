<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:10
 */

namespace App\OAuth;

use App\Enums\OAuthClientType;
use App\User;
use Carbon\Carbon;
use Laravel\Passport\Client as PassportClient;
use Wildside\Userstamps\Userstamps;

/**
 * Class Client
 * @package App\OAuth
 *
 * @property integer $id
 * @property integer|null $user_id
 * @property string $name
 * @property string $secret
 * @property string $redirect
 * @property boolean $personal_access_client
 * @property boolean $password_client
 * @property boolean $revoked
 *
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 *
 * @property-read integer $created_by
 * @property-read integer $updated_by
 *
 * @property-read OAuthClientType $type
 *
 * @property-read User|null $user
 */
class Client extends PassportClient
{

    use Userstamps;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the User that manages this client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Gives the PersonalAccessClients that belong to this Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personalAccessClients() {
        return $this->hasMany(PersonalAccessClient::class, 'client_id');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the OAuthClientType of this client.
     *
     * @return OAuthClientType
     */
    public function getTypeAttribute()
    {
        if($this->personal_access_client) {
            return OAuthClientType::PERSONAL();
        }

        if($this->password_client) {
            return OAuthClientType::PASSWORD();
        }

        if($this->redirect === '') {
            return OAuthClientType::CREDENTIALS();
        }

        return OAuthClientType::AUTH_CODE();
    }

}