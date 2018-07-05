<?php

namespace App;


use App\Traits\HasDescription;
use App\Traits\HasOptions;
use App\Traits\HasShortName;
use App\Traits\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Models\AuthorizableGroup;
use Roelhem\RbacGraph\Contracts\Models\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Database\Traits\HasMorphedRbacAssignments;
use Wildside\Userstamps\Userstamps;

/**
 * Class GroupCategory
 *
 * @package App
 *
 * @property integer $id
 * @property boolean $is_required
 * @property string $style
 *
 * @property-read Collection|Group[] $groups
 */
class GroupCategory extends Model implements RbacDatabaseAssignable, AuthorizableGroup
{

    use SoftDeletes;
    use Userstamps;
    use Sluggable;
    use Filterable;

    use HasShortName, HasDescription;
    use HasOptions;

    use HasMorphedRbacAssignments;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'group_categories';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['name','name_short', 'slug','style','description','is_required', 'options'];

    protected function defaultOptions(): array
    {
        return [
            'showOnPersonsPage' => true,
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Gives all the Groups that belong to this GroupCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups() {
        return $this->hasMany(Group::class, 'category_id');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTATION: AuthorizableGroup ------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    public function getAuthorizables()
    {
        return $this->groups;
    }

    public function getAuthorizableGroups()
    {
        return collect([]);
    }

    public function getDynamicRoles()
    {
        return collect([]);
    }

}
