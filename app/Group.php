<?php

namespace App;

use App\Interfaces\Rbac\RbacAuthorizable;
use App\Interfaces\Rbac\RbacRoleAssignable;
use App\Services\Rbac\Traits\DefaultRbacAuthorizable;
use App\Traits\HasDescription;
use App\Traits\HasShortName;
use App\Traits\Rbac\HasChildRoles;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\AuthorizableGroup;
use Roelhem\RbacGraph\Contracts\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Database\Traits\HasMorphedRbacAssignments;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Group
 *
 * @package App
 *
 * @property integer $id
 * @property string $category_id
 * @property string $slug
 * @property string $member_name
 * @property boolean $is_required
 *
 * @property-read GroupCategory $category
 * @property-read Collection|Person[] $persons
 *
 * @property-read string $style
 */
class Group extends Model implements RbacDatabaseAssignable, AuthorizableGroup
{

    use SoftDeletes;
    use Userstamps;
    use Sluggable;

    use HasShortName, HasDescription, HasMorphedRbacAssignments;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'groups';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['category_id','slug','name','name_short','description','member_name','is_required'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ACCESSOR METHODS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function getMemberNameAttribute($value) {
        if(empty($value)) {
            return $this->name_short;
        } else {
            return $value;
        }
    }

    public function getStyleAttribute() {
        return $this->category->style;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the GroupCategory where this Group belongs to.
     *
     * @return BelongsTo
     */
    public function category() {
        return $this->belongsTo(GroupCategory::class, 'category_id');
    }

    /**
     * Gives all the Persons that belong to this Group.
     *
     * @return BelongsToMany
     */
    public function persons() {
        return $this->belongsToMany(Person::class, 'person_group','group_id','person_id');
    }

    /**
     * Gives the GroupEmailAdresses that belong to this Group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emailAddresses() {
        return $this->hasMany(GroupEmailAddress::class, 'group_id');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTATION: AuthorizableGroup ------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //


    public function getAuthorizables()
    {
        return $this->persons;
    }

    public function getAuthorizableGroups()
    {
        return collect([$this->category]);
    }

}
