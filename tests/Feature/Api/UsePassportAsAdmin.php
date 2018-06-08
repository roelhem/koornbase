<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 13:39
 */

namespace Tests\Feature\Api;


use App\Services\Rbac\RbacGenerator;
use App\User;
use Laravel\Passport\Passport;

/**
 * Trait UsePassportAsAdmin
 *
 * Makes it easier to make API-Calls that are protected with Passport.
 *
 * @package Tests\Feature\Api
 */
trait UsePassportAsAdmin
{

    /**
     * Loads the rbac-structure into the database.
     */
    public function loadRbac() {
       resolve(RbacGenerator::class)->run();
    }

    /**
     * Sets passport such that you can use test API-calls as an Admin-account.
     *
     * @return User  the user that is used to navigate the database.
     */
    public function asAdmin() {

        $this->loadRbac();

        $this->defaultHeaders = ['Accept','application/json'];

        $user = factory(User::class)->create();
        if($user instanceof User) {
            $user->assignRole('admin');
        }
        Passport::actingAs($user);
        return $user;
    }

}