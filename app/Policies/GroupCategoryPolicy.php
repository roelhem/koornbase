<?php

namespace App\Policies;

use App\GroupCategory;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Policy that determines if a GroupCategory can be shown to the current user.
     *
     * @param User $user
     * @param GroupCategory $category
     * @return boolean
     */
    public function view(User $user, GroupCategory $category) {
        if($user->hasPermission('model.group_categories.view.all')) {
            return true;
        } elseif($user->hasPermission('model.group_categories.view.own')) {
            if($user->person === null) {
                return false;
            }

            $searchQuery = $user->person->groups()->whereHas('category', function($query) use ($category) {
                return $query->where('id','=',$category->id);
            });

            if($searchQuery->exists()) {
                return true;
            } else {
                return false;
            }
        }
    }
}
