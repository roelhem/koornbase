<?php

namespace App;

use App\Traits\HasShortName;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class ExpanseCategory extends Model
{

    use SoftDeletes;
    use Userstamps;
    use HasShortName;
    use Sluggable;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'expanse_categories';

    protected $dates = ['created_at','updated_at','deleted_at'];

    public function sluggable(): array
    {
        return ['slug'];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Expanses that belong to this ExpanseCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expanses() {
        return $this->hasMany(Expanse::class, 'category_id');
    }

    /**
     * Gives the Budgets that are associated with this ExpanseCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function budgets() {
        return $this->belongsToMany(Budget::class, 'budget_expanse_category', 'category_id','budget_id');
    }

}
