<?php

namespace App;

use App\Traits\Group\HasMembers;
use App\Traits\HasAssignedRoles;
use App\Traits\HasShortName;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
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
 * @property string $name
 * @property string $name_short
 * @property string $description
 * @property string $member_name
 * @property boolean $is_required
 */
class Group extends Model
{

    use SoftDeletes;
    use Userstamps;
    use Sluggable;

    use HasShortName, HasAssignedRoles;

    use HasMembers;

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

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the GroupCategory where this Group belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo(GroupCategory::class, 'category_id');
    }

    /**
     * Gives the Roles that were directly assigned to this Group. If a Role is assigned to a Group, all current
     * members of this group gain the permissions of the assigned Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignedRoles() {
        return $this->belongsToMany(Role::class, 'group_role','group_id','role_id');
    }

    /**
     * Gives the JobCategories where this Group is allowed. A Job that belongs to a JobCategory in this list
     * is allowed to be fulfilled by a Person that belongs to this Group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function allowedJobCategories() {
       return $this->belongsToMany(JobCategory::class,'job_category_group','group_id','category_id');
    }
}
