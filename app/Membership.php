<?php

namespace App;

use App\Contracts\OwnedByPerson;
use App\Enums\MembershipStatus;
use App\Traits\HasRemarks;
use App\Traits\BelongsToPerson;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

/**
 * Class Membership
 *
 * @package App
 *
 * @property integer|null $id
 * @property Carbon|null $application
 * @property Carbon|null $start
 * @property Carbon|null $end
 *
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 *
 * @property-read boolean $applied
 * @property-read boolean $started
 * @property-read boolean $ended
 *
 * @property-read MembershipStatus $status
 * @property-read Carbon|null $status_at
 */
class Membership extends Model implements OwnedByPerson
{

    use Userstamps;
    use Filterable;

    use HasRemarks, BelongsToPerson;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'memberships';

    protected $dates = ['application','start','end','created_at','updated_at'];
    protected $fillable = ['application','start','end','remarks'];

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

        if(!($at instanceof Carbon)) {
            $at = Carbon::parse($at);
        }

        return $this->application <= $at;
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

        if(!($at instanceof Carbon)) {
            $at = Carbon::parse($at);
        }

        return $this->start < $at;
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

        if(!($at instanceof Carbon)) {
            $at = Carbon::parse($at);
        }

        return $this->end < $at;
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

}
