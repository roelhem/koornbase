<?php

namespace App;

use App\Enums\MembershipStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Wildside\Userstamps\Userstamps;

/**
 * Class Membership
 *
 * @package App
 *
 * @property integer|null $id
 * @property integer|null $person_id
 * @property Carbon|null $application
 * @property Carbon|null $start
 * @property Carbon|null $end
 * @property string|null $remarks
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
 * @property-read integer $status
 * @property-read Carbon|null $status_at
 */
class Membership extends Model
{

    use Userstamps;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'memberships';

    protected $dates = ['application','start','end','created_at','updated_at'];
    protected $fillable = ['application','start','end','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Whether or not the application date is set and in the past.
     *
     * @return bool
     */
    public function getAppliedAttribute() {
        return $this->application !== null && $this->application->isPast();
    }

    /**
     * Whether or not the start date is set and in the past.
     *
     * @return bool
     */
    public function getStartedAttribute() {
        return $this->start !== null && $this->start->isPast();
    }

    /**
     * Whether or not the end date is set and in the past.
     *
     * @return bool
     */
    public function getEndedAttribute() {
        return $this->end !== null && $this->end->isPast();
    }

    /**
     * Returns the status of this Membership.
     *
     * The value is an enum element of \App|Enums\MembershipStatus .
     *
     * @return int
     */
    public function getStatusAttribute() {
        if ($this->ended) {
            return MembershipStatus::FormerMember;
        } elseif ($this->started) {
            return MembershipStatus::Member;
        } elseif ($this->applied) {
            return MembershipStatus::Novice;
        } else {
            return MembershipStatus::Outsider;
        }
    }

    /**
     * Returns the date on which the membership changed to the current status.
     *
     * @return Carbon|null
     */
    public function getStatusAtAttribute() {
        switch ($this->status) {
            case MembershipStatus::FormerMember: return $this->end;
            case MembershipStatus::Member: return $this->start;
            case MembershipStatus::Novice: return $this->application;
            case MembershipStatus::Outsider:
            default: return null;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Person where this Membership belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }

}
