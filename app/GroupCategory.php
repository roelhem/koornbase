<?php

namespace App;

use App\Traits\HasAssignedRoles;
use App\Traits\HasDescription;
use App\Traits\HasOptions;
use App\Traits\HasShortName;
use App\Traits\HasStringPrimaryKey;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

/**
 * Class GroupCategory
 *
 * @package App
 *
 * @property integer $id
 * @property boolean $is_required
 * @property string $style
 */
class GroupCategory extends Model
{

    use SoftDeletes;
    use Userstamps;
    use Sluggable;

    use HasShortName, HasDescription;

    use HasOptions, HasAssignedRoles;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'group_categories';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['id','name','name_short', 'slug','style','description','is_required','groups'];

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


}
