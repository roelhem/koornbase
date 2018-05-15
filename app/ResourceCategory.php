<?php

namespace App;

use App\Traits\HasShortName;
use App\Traits\HasStringPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class ResourceCategory extends Model
{

    use SoftDeletes;
    use Userstamps;
    use HasShortName;
    use HasStringPrimaryKey;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'resource_categories';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = ['created_at','updated_at','deleted_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all the Resources that belong to this ResourceCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources() {
        return $this->hasMany(Resource::class, 'category_id');
    }

}
