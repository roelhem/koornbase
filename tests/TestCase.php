<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\RbacGraphException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- API REQUESTS --------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Uses the headers for a HTTP-request to one of the json-api's.
     */
    public function withApiHeaders()
    {
        $this->defaultHeaders = ['Accept','application/json'];
    }

    /**
     * Sets the passport requests to the provided user
     *
     * @param User $user
     * @return User
     */
    public function asUser($user)
    {
        $this->withApiHeaders();
        Passport::actingAs($user);
        return $user;
    }

    /**
     * Sets for Passport requests as a user with the Super role.
     *
     * @return User
     */
    public function asSuper()
    {
        /** @var Node $super */
        $super = \Rbac::superRole()->getNode();
        /** @var User $user */
        $user = factory(User::class)->create();
        try {
            $user->assignNode($super);
        } catch (RbacGraphException $exception) {
            throw with($exception);
        }


        $this->asUser($user);
        return $user;
    }
}
