<?php

namespace App\Policies;

use App\User;
use App\Person;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the person.
     *
     * @param  \App\User  $user
     * @param  \App\Person  $person
     * @return mixed
     */
    public function view(User $user, Person $person)
    {
        if($user->hasPermission('model.persons.view.all')) {
            return true;
        }

        if($user->hasPermission('model.persons.view.self')) {
            return $user->person_id === $person->id;
        }

        if($user->hasPermission('model.persons.view.created')) {
            return $user->id === $person->created_by;
        }

        return false;
    }

    /**
     * Determine whether the user can create people.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->hasPermission('model.persons.create')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the person.
     *
     * @param  \App\User  $user
     * @param  \App\Person  $person
     * @return mixed
     */
    public function update(User $user, Person $person)
    {
        if($user->hasPermission('model.persons.update.all')) {
            return true;
        }

        if($user->hasPermission('model.persons.update.self')) {
            return $user->person_id === $person->id;
        }

        if ($user->hasPermission('model.persons.update.created')) {
            return $user->id === $person->created_by;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the person.
     *
     * @param  \App\User  $user
     * @param  \App\Person  $person
     * @return mixed
     */
    public function delete(User $user, Person $person)
    {
        if($user->hasPermission('model.persons.delete.all')) {
            return true;
        }

        if($user->hasPermission('model.person.delete.created')) {
            return $user->id === $person->created_by;
        }

        return false;
    }
}
