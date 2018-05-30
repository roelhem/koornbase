<?php

namespace App;

use App\Traits\HasRemarks;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

/**
 * Class KoornbeursCard
 * @package App
 *
 * @property integer $id
 * @property integer|null $owner_id
 * @property string|null $ref
 * @property string|null $version
 * @property Carbon|null $activated_at
 * @property Carbon|null $deactivated_at
 *
 * @property-read Person|null $owner
 */
class KoornbeursCard extends Model
{

    use Userstamps;

    use HasRemarks;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'koornbeurs_cards';

    protected $dates = ['activated_at','deactivated_at','created_at', 'updated_at'];
    protected $fillable = ['id','ref','version','activated_at','deactivated_at','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Scope that gives only the active KoornbeursCard instances.
     *
     * @param Builder $query
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeActive($query, $at = null) {
        if($at === null) {
            $at = Carbon::now();
        }

        if(!($at instanceof Carbon)) {
            $at = Carbon::parse($at);
        }

        $query->whereNotNull('activated_at')
            ->where('activated_at','<=',$at)
            ->where(function($subQuery) use ($at) {
                return $subQuery->where('deactivated_at','>=',$at)->orWhereNull('deactivated_at');
            });

        return $query;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Owner of this KoornbeursCard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo(Person::class, 'owner_id');
    }

}
