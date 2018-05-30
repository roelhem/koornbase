<?php

namespace App;

use App\Traits\Group\HasMembers;
use App\Traits\HasAssignedRoles;
use App\Traits\HasShortName;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 *
 * @property-read GroupCategory $category
 *
 * @property-read string $style
 */
class Group extends Model
{

    use SoftDeletes;
    use Userstamps;
    use Sluggable;

    use HasShortName, HasAssignedRoles;

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

}
