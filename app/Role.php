<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

/**
 * Class Role
 * @package App
 *
 * @property string $name The name of the role
 * @property boolean $for_user If this role may be assigned to a User
 * @property boolean $for_group If this role may be assigned to a Group
 * @property boolean $for_group_title If this role may be assigned to a GroupTitle
 * @property boolean $for_group_category If this role may be assigend to a GroupCategory
 */
class Role extends Model
{

    use Userstamps;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'roles';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id','name','description','is_required','is_visible','for_user','for_group',
                            'for_group_title','for_group_category'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- METHODS for assigning RBAC ENTITIES ---------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Assigns this role to another Model. (This will only work if the other model can have Roles be assigned to.)
     *
     * @param Model $model
     * @return $this
     */
    public function assignTo(Model $model) {
        if($model instanceof Role) { $this->assignToRole($model); }
        elseif($model instanceof User) { $this->assignToUser($model); }
        elseif($model instanceof Group) { $this->assignToGroup($model); }
        elseif($model instanceof GroupTitle) { $this->assignToGroupTitle($model); }
        elseif($model instanceof GroupCategory) { $this->assignToGroupCategory($model); }
        return $this;
    }

    /**
     * Assigns this Role to another Role as a Child. ($this will be the child and $role will be the parent.)
     *
     * @param Role|string $role
     * @return $this
     */
    public function assignToRole($role) {
        $this->parentRoles()->attach($role);
        return $this;
    }

    /**
     * Assigns this Role to a User.
     *
     * @param User|integer $user
     * @return $this
     */
    public function assignToUser($user) {
        $this->parentUsers()->attach($user);
        return $this;
    }

    /**
     * Assigns this Role to a Group. If a string is given for the $group parameter, the method will try to find
     * a group that has a slug equal to the provided string.
     *
     * @param Group|integer|string $group
     * @return $this
     */
    public function assignToGroup($group) {
        if(is_string($group)) {
            $group = Group::findBySlug($group);
        }
        $this->parentGroups()->attach($group);
        return $this;
    }

    /**
     * Assigns this Role to a GroupTitle. If a string is given for the $groupTitle parameter, the method will try
     * to find a GroupTitle that has a slug equal to the provided string.
     *
     * @param Group|integer|string $groupTitle
     * @return $this
     */
    public function assignToGroupTitle($groupTitle) {
        if(is_string($groupTitle)) {
            $groupTitle = GroupTitle::findBySlug($groupTitle);
        }
        $this->parentGroupTitles()->attach($groupTitle);
        return $this;
    }

    /**
     * Assigns this Role to a GroupCategory.
     *
     * @param Group|string $groupCategory
     * @return $this
     */
    public function assignToGroupCategory($groupCategory) {
        $this->parentGroupCategories()->attach($groupCategory);
        return $this;
    }

    /**
     * Assigns a provided Role or Permission to this Role as a child. ($this will be the parent and $model
     * will be the child). Only Roles and Permissions can be assigned with this method.
     *
     * @param Model $model
     * @return $this
     */
    public function assign(Model $model) {
        if($model instanceof Role) { $this->assignRole($model); }
        elseif($model instanceof Permission) { $this->assignPermission($model); }
        return $this;
    }

    /**
     * Assigns the provided Role to this Role as a child. ($this will be the parent and $role will be the child).
     *
     * @param Role|string $role
     * @return $this
     */
    public function assignRole($role) {
        $this->childRoles()->attach($role);
        return $this;
    }

    /**
     * Assigns the provided Permission to this Role.
     *
     * @param Permission|string $permission
     * @return $this
     */
    public function assignPermission($permission) {
        $this->childPermissions()->attach($permission);
        return $this;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all Users where this Role is directly assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentUsers() {
        return $this->belongsToMany(User::class, 'user_role',
                                'role_id','user_id');
    }

    /**
     * Gives all Groups where this Role is directly assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentGroups() {
        return $this->belongsToMany(Group::class, 'group_role',
                                'role_id','group_id');
    }

    /**
     * Gives all GroupTitles where this Role is directly assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentGroupTitles() {
        return $this->belongsToMany(GroupTitle::class, 'group_title_role',
                                'role_id','group_title_id');
    }

    /**
     * Gives all the GroupCategories where this Role is directly assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentGroupCategories() {
        return $this->belongsToMany(GroupCategory::class, 'group_category_role',
                                'role_id','user_id');
    }

    /**
     * Gives all the direct parent Roles of this Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentRoles() {
        return $this->belongsToMany(Role::class, 'role_role',
                                'child_id','parent_id');
    }

    /**
     * Gives all the direct child Roles of this Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function childRoles() {
        return $this->belongsToMany(Role::class, 'role_role',
                                'parent_id','child_id');
    }

    /**
     * Gives all the Permissions that are directly assigned to this Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function childPermissions() {
        return $this->belongsToMany(Permission::class, 'role_permission',
                                'role_id','permission_id');
    }

}
