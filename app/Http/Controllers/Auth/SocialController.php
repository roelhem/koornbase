<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OAuthProvider;
use App\UserAccount;
use App\Http\Controllers\Controller;

/**
 * Class SocialController
 *
 * This Controller handles the OAuth login.
 *
 * @package App\Http\Controllers\Auth
 */
class SocialController extends Controller
{

    /**
     * Validates if the provider can be used.
     *
     * @param string $provider
     */
    protected function validateProvider($provider) {

        if(!OAuthProvider::get($provider)->active) {
            abort(404, "Couldn't find the OAuth provider $provider.");
        }
    }

    /**
     * The action that redirects the user to the external provider.
     *
     * @param string $provider
     * @return mixed
     */
    public function redirectToProvider($provider) {
        $this->validateProvider($provider);

        return \Socialite::driver($provider)->scopes(['user_photos'])->fields(['albums'])->redirect();
    }


    /**
     * The action that handle the callback of the external providers.
     *
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider) {
        $this->validateProvider($provider);

        $user = \Socialite::driver($provider)->user();

        // Check if the user is also present in the database.
        $account = UserAccount::query()->where('provider','=', $provider)
                                            ->where('ref_id', '=', $user->getId())
                                            ->first();
        if($account instanceof UserAccount) {

            // If the account was found, check if someone logged in yet.
            $loggedUser = \Auth::user();
            if($loggedUser === null) {

                // If no-one was logged in, authenticate the user where this account belongs to.
                \Auth::login($account->user);
                // And save the updated account data.
                $account->oauthUser = $user;
                // Then redirect to the page for the account settings.
                return redirect()->route('me');

            } else {

                // If someone was already logged in, check if the given account is his.
                if($account->user_id === $loggedUser->id) {
                    // If this is the case, update the account data.
                    $account->oauthUser = $user;
                    // And then redirect to the page for the account settings.
                    return redirect()->route('me');

                } else {

                    // If the account was someone elses, give some options to the current user what to do next.
                    // TODO: Give the user some options.
                    // Then redirect to the page with the account settings.
                    return redirect()->route('me');
                }
            }


        } else {

            // If the account was not found, check if someone logged in yet.
            $loggedUser = \Auth::user();
            if($loggedUser === null) {

                // If no-one was logged in and the account was not found, try to match it with a person in
                // the database to create a new account.
                // TODO: Trying to mach the account to a person.
                // It it didn't work, retry to login.
                return redirect()->route('login');

            } else {

                // If someone was logged in, check if this user already have an account of the same provider
                // connected to his/her account.
                $account = $loggedUser->accounts()->where('provider','=',$provider)->first();

                if($account === null) {

                    // If the user didn't have an account form that provider coupled to his account yet,
                    // Couple this new account to the current user.
                    UserAccount::create([
                        'user_id' => $loggedUser->id,
                        'provider' => $provider,
                        'oauthUser' => $user
                    ]);

                    return redirect()->route('me');

                } else {

                    // If the account was of nobody, but the current user already had an account from this provider,
                    // TODO: Give a change to ignore the login.
                    // Then redirect to the page with the account settings.
                    return redirect()->route('me');

                }

            }



        }


    }

}
