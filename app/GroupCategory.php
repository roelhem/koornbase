<?php

namespace App;

use App\Traits\HasAssignedRoles;
use App\Traits\HasOptions;
use App\Traits\HasShortName;
use App\Traits\HasStringPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

/**
 * Class GroupCategory
 *
 * @package App
 *
 * @property string $id
 * @property string $name
 * @property string $name_short
 * @property string $description
 * @property boolean $is_required
 * @property string $style
 */
class GroupCategory extends Model
{

    use SoftDeletes;
    use Userstamps;
    use HasShortName;
    use HasStringPrimaryKey;
    use HasAssignedRoles;

    use HasOptions;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'group_categories';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['id','name','name_short','description','is_required','groups'];

    protected function defaultOptions(): array
    {
        return [
            'showOnPersonsPage' => true,
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CREATING METHODS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    public function new($params = []) {
        $res = new Group($params);
        $res->category_id = $this->id;
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

    /**
     * Gives the Roles that were directly assigned to this GroupCategory. If a Role is assigned to a GroupCategory,
     * all current members of Groups that belong to this GroupCategory gain the permissions of the assigned Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignedRoles() {
        return $this->belongsToMany(Role::class, 'group_category_role',
                                    'group_category_id','role_id');
    }


}
