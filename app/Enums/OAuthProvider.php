<?php

namespace App\Enums;

use App\Enums\Traits\HasConfigFile;
use App\User;
use App\UserAccount;
use MabeEnum\Enum;
use Symfony\Component\Yaml\Yaml;

/**
 * Class OAuthProviders
 * @package App\Enums
 *
 * @property-read string $value
 * @property-read string $displayName
 * @property-read string $title
 * @property-read string|null $description
 * @property-read string|null $usage
 * @property-read boolean $active
 */
final class OAuthProvider extends Enum
{

    use HasConfigFile;

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  ENUM-VALUE DEFINITIONS  ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    const FACEBOOK = 'facebook';
    const GOOGLE = 'google';
    const TWITTER = 'twitter';
    const GITHUB = 'github';
    const TUDELFT = 'tudelft';


    // ---------------------------------------------------------------------------------------------------------- //
    // --------  USING THE CONFIG-FILE  ------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the parsed content of the config file.
     *
     * @return array
     */
    protected static function parseFile()
    {
        $filename = str_replace('.php','.yaml',__FILE__);
        return Yaml::parseFile($filename);
    }

    /**
     * Returns the default config-file. This is used for the implementation of the magic methods.
     *
     * @return array
     */
    protected function defaultConfig()
    {
        return [
            'displayName' => mb_convert_case($this->value, MB_CASE_TITLE),
            'title' => $this->getCamelCaseName(),
            'description' => null,
            'usage' => null,
            'active' => false,
            'ranking' => [
                'avatar' => 0,
                'secure' => 0
            ],
            'style' => [
                'bootstrap' => []
            ],
            'links' => []
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  USER HELPER METHODS  --------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gets the user-account that corresponds to the OAuthProvider of this element. If no user-parameter was given,
     * the current user will be used.
     *
     * This function will return null if there was no account to be found.
     *
     * @param User|integer|null $user
     * @return UserAccount|null
     */
    public function getAccount($user = null)
    {
        if($user === null) {
            $user = \Auth::user();
        } elseif(is_integer($user)) {
            $user = User::find($user);
        }

        if(!($user instanceof User)) {
            return null;
        }

        $account = $user->accounts()->where('provider','=', $this->value)->first();
        if($account instanceof UserAccount) {
            return $account;
        } else {
            return null;
        }
    }

}
