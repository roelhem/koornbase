<?php

namespace App;

use App\Contracts\OwnedByPerson;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\HasRemarks;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;

/**
 * Class KoornbeursCard
 *
 * @package App
 * @property integer $id
 * @property integer|null $owner_id
 * @property string|null $ref
 * @property string|null $version
 * @property Carbon|null $activated_at
 * @property Carbon|null $deactivated_at
 * @property-read boolean $is_active
 * @property-read Person|null $owner
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard active($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard filter($input = array(), $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard inactive($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard ownedBy($person_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KoornbeursCard whereLike($column, $value, $boolean = 'and')
 * @mixin \Eloquent
 */
class KoornbeursCard extends Model implements OwnedByPerson
{

    use Userstamps;
    use Filterable, Sortable;

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
        $at = \Parse::date($at, true);

        if($this->activated_at === null || $this->activated_at >= $at) {
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
        $at = \Parse::date($at, true);

        $query->whereNotNull('activated_at')
            ->where('activated_at','<=',$at)
            ->where(function($subQuery) use ($at) {
                return $subQuery->where('deactivated_at','>',$at)->orWhereNull('deactivated_at');
            });

        return $query;
    }

    /**
     * Scope that gives only the inactive KoornbeursCard instances.
     *
     * @param Builder $query
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeInactive($query, $at = null) {
        $at = \Parse::date($at, true);

        $query->whereNull('activated_at')
            ->orWhere('activated_at','>', $at)
            ->orWhere('deactivated_at','<=', $at);

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
