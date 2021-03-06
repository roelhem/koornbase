<?php

namespace App;

use App\Contracts\OwnedByPerson;
use App\Enums\MembershipStatus;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\HasRemarks;
use App\Traits\BelongsToPerson;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;

/**
 * Class Membership
 *
 * @package App
 * @property integer|null $id
 * @property Carbon|null $application
 * @property Carbon|null $start
 * @property Carbon|null $end
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 * @property-read boolean $applied
 * @property-read boolean $started
 * @property-read boolean $ended
 * @property-read MembershipStatus $status
 * @property-read Carbon|null $status_at
 * @property-read Carbon|null $upper_bound
 * @property-read Carbon|null $lower_bound
 * @property-read \App\Person $owner
 * @property-read \App\Person $person
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership filter($input = array(), $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership formerMember($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership member($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership novice($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership outsider($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership ownedBy($person_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership status($status, $at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Membership whereLike($column, $value, $boolean = 'and')
 * @mixin \Eloquent
 */
class Membership extends Model implements OwnedByPerson
{

    use Userstamps;
    use Filterable, Sortable;

    use HasRemarks, BelongsToPerson;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'memberships';

    protected $dates = ['application','start','end','created_at','updated_at'];
    protected $fillable = ['person_id','application','start','end','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns if the application date is set and before the provided time.
     *
     * @param Carbon|string|null $at
     * @return bool
     */
    public function getApplied($at = null) {
        if($this->application === null) {
            return false;
        }

        return $this->application <= \Parse::date($at, true);
    }

    /**
     * Returns if the start date is set and before the provided time.
     *
     * @param Carbon|string|null $at
     * @return bool
     */
    public function getStarted($at = null) {
        if($this->start === null) {
            return false;
        }

        return $this->start < \Parse::date($at, true);
    }

    /**
     * Returns if the end date is set and before the provided time.
     *
     * @param Carbon|string|null $at
     * @return bool
     */
    public function getEnded($at = null) {
        if($this->end === null) {
            return false;
        }

        return $this->end < \Parse::date($at, true);
    }

    /**
     * Returns the status of this membership at the given moment.
     *
     * @param Carbon|string|null $at
     * @return MembershipStatus
     */
    public function getStatus($at = null) {
        if($this->getEnded($at)) {
            return MembershipStatus::FORMER_MEMBER();
        } elseif ($this->getStarted($at)) {
            return MembershipStatus::MEMBER();
        } elseif ($this->getApplied($at)) {
            return MembershipStatus::NOVICE();
        } else {
            return MembershipStatus::OUTSIDER();
        }
    }

    /**
     * Returns the date on which the membership status changed to the status on the given moment.
     *
     * @param Carbon|string|null $at
     * @return Carbon|null
     */
    public function getStatusSince($at = null) {
        $status = $this->getStatus($at);
        return $status->getTimestamp($this);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @param Builder $query
     * @param MembershipStatus|int $status
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeStatus($query, $status, $at = null) {
        if(MembershipStatus::OUTSIDER()->is($status)) {
            return $this->scopeOutsider($query, $at);
        } elseif(MembershipStatus::NOVICE()->is($status)) {
            return $this->scopeNovice($query, $at);
        } elseif(MembershipStatus::MEMBER()->is($status)) {
            return $this->scopeMember($query, $at);
        } elseif(MembershipStatus::FORMER_MEMBER()->is($status)) {
            return $this->scopeFormerMember($query, $at);
        }
    }

    /**
     * @param Builder $query
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeOutsider($query, $at = null) {
        $at = \Parse::date($at, true);

        return $query->where(function($query) use ($at) {
            /** @var Builder $query */
            return $query->whereNull('application')->orWhere('application','>', $at);
        })->where(function($query) use ($at) {
            /** @var Builder $query */
            return $query->whereNull('start')->orWhere('start','>', $at);
        })->where(function($query) use ($at) {
            /** @var Builder $query */
            return $query->whereNull('end')->orWhere('end','>', $at);
        });
    }

    /**
     * @param Builder $query
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeNovice($query, $at = null) {
        $at = \Parse::date($at, true);

        return $query->whereNotNull('application')->where('application', '<=', $at)->where(function($query) use ($at) {
            /** @var Builder $query */
            return $query->whereNull('start')->orWhere('start','>', $at);
        })->where(function($query) use ($at) {
            /** @var Builder $query */
            return $query->whereNull('end')->orWhere('end','>', $at);
        });
    }

    /**
     * @param Builder $query
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeMember($query, $at = null) {
        $at = \Parse::date($at, true);

        return $query->whereNotNull('start')->where('start', '<=', $at)->where(function($query) use ($at) {
            /** @var Builder $query */
            return $query->whereNull('end')->orWhere('end','>', $at);
        });
    }

    /**
     * @param Builder $query
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeFormerMember($query, $at = null) {
        $at = \Parse::date($at, true);

        return $query->whereNotNull('end')->where('end', '<=', $at);
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Whether or not the application date is set and in the past.
     *
     * @return bool
     */
    public function getAppliedAttribute() {
        return $this->getApplied();
    }

    /**
     * Whether or not the start date is set and in the past.
     *
     * @return bool
     */
    public function getStartedAttribute() {
        return $this->getStarted();
    }

    /**
     * Whether or not the end date is set and in the past.
     *
     * @return bool
     */
    public function getEndedAttribute() {
        return $this->getEnded();
    }

    /**
     * Returns the status of this Membership.
     *
     * The value is an enum element of \App|Enums\MembershipStatus .
     *
     * @return MembershipStatus
     */
    public function getStatusAttribute() {
        return $this->getStatus();
    }

    /**
     * Returns the date on which the membership changed to the current status.
     *
     * @return Carbon|null
     */
    public function getStatusAtAttribute() {
        return $this->getStatusSince();
    }

    /**
     * Returns the last DateTime when this membership was active for the Person it belongs to. If `null` is returned,
     * there is no upper bound for the active state of this membership.
     *
     * @return Carbon|null
     */
    public function getUpperBoundAttribute() {
        return $this->end;
    }

    /**
     * Returns the first DateTime when this membership was active for the Person it belongs to. If `null` is returned,
     * there is no lower bound for the active state of this membership.
     *
     * @return Carbon|null
     */
    public function getLowerBoundAttribute() {
        return $this->application ?? $this->start;
    }

}
