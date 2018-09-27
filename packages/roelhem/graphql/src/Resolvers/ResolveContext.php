<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 11:43
 */

namespace Roelhem\GraphQL\Resolvers;


use Illuminate\Contracts\Auth\Guard;

class ResolveContext
{

    /** @var Guard */
    protected $guard;

    /**
     * ResolveContext constructor.
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }


    /**
     * Returns the User of the current request, or null if no user was authenticated.
     *
     * @return \App\User|null
     */
    public function user()
    {
        /** @var \App\User|null $user */
        $user = $this->guard->user();
        return $user;
    }

}