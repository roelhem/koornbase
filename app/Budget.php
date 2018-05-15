<?php

namespace App;

use App\Traits\HasShortName;
use App\Traits\HasStartEnd;
use App\Traits\Sluggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

/**
 * Class Budget
 *
 * @package App
 *
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string $name_short
 * @property string $description
 * @property Carbon $start
 * @property Carbon $end
 * @property double $amount
 * @property integer $exact_ref
 * @property string $remarks
 */
class Budget extends Model
{

    use SoftDeletes;
    use Userstamps;
    use Sluggable;


    use HasShortName, HasStartEnd;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'budgets';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = ['start','end','created_at','updated_at','deleted_at'];

    protected $fillable = ['slug','name','name_short','description','start','end','amount','exact_ref','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Expanses on this Budget.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expanses() {
        return $this->hasMany(Expanse::class, 'budget_id');
    }

    /**
     * Gives the ExpanseCategories that are associated with this Budget.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function expanseCategories() {
        return $this->belongsToMany(ExpanseCategory::class, 'budget_expanse_category',
            'budget_id', 'category_id')->withPivot('is_default');
    }

}
