<?php

namespace App\Enums;

use App\User;

final class OAuthProviders extends Enum
{
    const Facebook = 'facebook';
    const Google = 'google';
    const Twitter = 'twitter';
    const Github = 'github';
    const TUDelft = 'tudelft';

    /**
     * Returns the OAuthProviders in an order such that the best image has the highest change to be picked.
     * As the main avatar.
     *
     * @return string[]
     */
    public static function ordeningAvatar() {
        return [self::Facebook, self::Twitter, self::Google, self::TUDelft, self::Github];
    }

    /**
     * Returns the order in which the OAuthProviders should be presented in a list.
     *
     * @return array
     */
    public static function ordeningSocial() {
        return [self::Google, self::Facebook, self::Twitter, self::TUDelft, self::Github];
    }

    /**
     * Gets the full name of the OAuth provider to display
     *
     * @param  string  $value
     * @return string
     */
    public static function getDisplayName(string $value): string
    {
        switch ($value) {
            case self::Facebook:
                return 'Facebook';
            case self::Google:
                return 'Google';
            case self::Twitter:
                return 'Twitter';
            case self::Github:
                return 'GitHub';
            case self::TUDelft:
                return 'TU-Delft';
            default:
                return ucfirst($value);
        }
    }

    /**
     * Gets an icon class that represents of the logo of the OAuthProviders.
     *
     * @param string $value
     * @return string
     */
    public static function getLogoIcon(string $value): string
    {
        switch ($value) {
            case self::TUDelft:
                return 'fa-university';
            case self::Github:
                return 'fa-github-alt';
            default:
                return "fa-{$value}";
        }
    }

    /**
     * Gets the button class of the OAuthProviders.
     *
     * @param string $value
     * @return string
     */
    public static function getButtonClass(string $value): string
    {
        switch ($value) {
            case self::TUDelft:
                return "btn-outline-primary";
            default:
                return "btn-{$value}";
        }
    }

    /**
     * Returns if the given OAuthProvider is active (can be used to login).
     *
     * @param string $value
     * @return bool
     */
    public static function getIsActive(string $value): bool
    {
        switch ($value) {
            case self::Facebook:
            case self::Github:
            case self::Google:
                return true;
            default:
                return false;
        }
    }

    /**
     * Returns the account of the given $user and provider.
     *
     * If no User was given, the current user is used.
     *
     * @param string $value
     * @param null $user
     * @return string|null
     */
    public static function getAccount(string $value, $user = null)
    {
        if($user === null) {
            $user = \Auth::user();
        } elseif(is_integer($user)) {
            $user = User::find($user);
        }

        if(!($user instanceof User)) {
            return null;
        }

        return $user->accounts()->where('provider', '=',$value)->first();
    }
}
