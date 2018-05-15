<?php

namespace App;

use App\Traits\HasShortName;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class RevenueCategory extends Model
{

    use Userstamps;
    use SoftDeletes;
    use HasShortName;
    use Sluggable;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'revenue_categories';

    protected $dates = ['created_at','updated_at','deleted_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all the Revenues that belongs to this Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function revenues() {
        return $this->hasMany(Revenue::class, 'category_id');
    }

}
