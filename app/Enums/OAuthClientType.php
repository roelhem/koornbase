<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 15:53
 */

namespace App\Enums;


use App\Enums\Traits\HasGraphQLEnumType;
use App\OAuth\Client;
use Laravel\Passport\ClientRepository;
use MabeEnum\Enum;


/**
 * Class OAuthClientType
 *
 * An enum of the different OAuthClient types.
 *
 * @package App\Enums
 *
 *
 * @method static OAuthClientType PERSONAL()
 * @method static OAuthClientType PASSWORD()
 * @method static OAuthClientType CREDENTIALS()
 * @method static OAuthClientType AUTH_CODE()
 *
 */
final class OAuthClientType extends Enum
{

    use HasGraphQLEnumType;

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  ENUM-VALUE DEFINITIONS  ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    const PERSONAL = 0;
    const PASSWORD = 1;
    const CREDENTIALS = 2;
    const AUTH_CODE = 3;

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  GraphQL OPTIONS  ------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    protected static function getDescription()
    {
        return 'This `Enum`-type contains the different types of OAuthClients that exist for the OAuth server of the KoornBase.';
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // --------  HELPER METHODS  -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the user ID for the OAuthClient with this OAuthClientType and the provided arguments.
     *
     * @param array $args
     * @return \Laravel\Passport\Client
     */
    public function create($args = [])
    {
        /** @var ClientRepository $clients */
        $clients = app(ClientRepository::class);

        $name = array_get($args, 'name');
        $redirect = array_get($args, 'redirect') ?? 'http://localhost';
        $user_id = array_get($args, 'user_id');

        if($user_id === null && \Auth::user() !== null) {
            $user_id = \Auth::user()->id;
        }


        // Pick the right constructor
        switch ($this->getValue()) {
            case self::PERSONAL:
                return $clients->createPersonalAccessClient($user_id, $name, $redirect);
            case self::PASSWORD:
                return $clients->createPasswordGrantClient($user_id, $name, $redirect);
            case self::CREDENTIALS:
                return $clients->create($user_id, $name, '');
            case self::AUTH_CODE:
                return $clients->create($user_id, $name, $redirect);
        }


    }

    public function getGraphQLTypeName()
    {
        switch ($this->getValue()) {
            case self::PERSONAL: return 'OAuthPersonalClient';
            case self::PASSWORD: return 'OAuthPasswordClient';
            case self::CREDENTIALS: return 'OAuthCredentialsClient';
            case self::AUTH_CODE: return 'OAuthAuthCodeClient';
            default: return 'OAuthClient';
        }
    }



}