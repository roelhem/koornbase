<?php

namespace App;

use App\Contracts\OwnedByPerson;
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
 * @property-read boolean $is_active
 * @property-read Person|null $owner
 */
class KoornbeursCard extends Model implements OwnedByPerson
{

    use Userstamps;

    use HasRemarks;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'koornbeurs_cards';

    protected $dates = ['activated_at','deactivated_at','created_at', 'updated_at'];
    protected $fillable = ['id','owner_id','ref','version','activated_at','deactivated_at','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTERS -------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns if this KoornbeursCard is active at the given moment $at. If $at is ommitted or `null`, the current
     * moment wil be used.
     *
     * @param Carbon|string|null $at
     * @return boolean
     */
    public function isActive($at = null) {
        if(!($at instanceof Carbon)) {
            $at = Carbon::parse($at);
        }

        if($this->activated_at === null || $this->activated_at > $at) {
            return false;
        }

        if($this->deactivated_at !== null && $this->deactivated_at < $at) {
            return false;
        }

        return true;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Calculated attribute that tells if this card is active or not at the current moment.
     *
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        return $this->isActive();
    }

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

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTS: OwnedByPerson -------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- /

    /** @inheritdoc */
    public function getOwner()
    {
        return $this->owner;
    }

    /** @inheritdoc */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /** @inheritdoc */
    public function scopeOwnedBy($query, $person_id)
    {
        return $query->where('owner_id','=',$person_id);
    }

}
