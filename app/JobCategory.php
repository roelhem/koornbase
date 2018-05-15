<?php

namespace App;

use App\Traits\HasShortName;
use App\Traits\HasStringPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class JobCategory extends Model
{

    use SoftDeletes;
    use Userstamps;
    use HasShortName;
    use HasStringPrimaryKey;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'job_categories';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Jobs that belong to this JobCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs() {
        return $this->hasMany(Job::class, 'category_id');
    }

    /**
     * Gives the Groups that can fulfill this JobCategory. This means that current members of a Group can fullfill
     * Jobs that belong to this JobCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fulfillingGroups() {
        return $this->belongsToMany(Group::class,'job_category_group','category_id','group_id');
    }

}
