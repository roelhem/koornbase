<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 09-05-18
 * Time: 13:44
 */

namespace App\Traits\Person;


use App\Membership;
use Illuminate\Database\Query\Builder;
use App\Helpers\MembershipStatusChange;
use App\Enums\MembershipStatus;
use Carbon\Carbon;

/**
 * Trait PersonHasMemberships
 * @package App\Traits
 *
 * @property-read integer $membership_status
 * @property-read Carbon|null $membership_status_since
 *
 * @property-read Membership[] $memberships
 * @property-read MembershipStatusChange[] $membership_status_changes
 */
trait HasMemberships
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the last MembershipStatusChange of this Person before $since.
     *
     * If no $since parameter is given, the current time will be used.
     *
     * @param Carbon|null $at
     * @return MembershipStatusChange|null
     */
    public function getLastMembershipStatusChange($at = null)
    {
        if($at === null) {
            $at = Carbon::today()->toDateString();
        }

        $result = $this->membershipStatusChanges()->whereDate('date', '<=', $at)
            ->orderByDesc('date')->first();

        if($result instanceof MembershipStatusChange) {
            return $result;
        } else {
            return null;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the membership status of this Person.
     *
     * The value is an enum element of \App|Enums\MembershipStatus .
     *
     * @return integer
     */
    public function getMembershipStatusAttribute()
    {
        $res = $this->getLastMembershipStatusChange();
        if($res === null) {
            return MembershipStatus::Outsider;
        } else {
            return $res->status;
        }
    }

    /**
     * Returns the membership status of this Person.
     *
     * The value is an enum element of \App|Enums\MembershipStatus .
     *
     * @return Carbon|null
     */
    public function getMembershipStatusSinceAttribute()
    {
        $res = $this->getLastMembershipStatusChange();
        if($res === null) {
            return null;
        } else {
            return $res->date;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * A scope that only selects the Persons with the given $status at the date given by $at.
     *
     * If the last parameter $at was not given, the current date will be used.
     *
     * @param Builder $query
     * @param integer[]|integer $status
     * @param Carbon|string|null $at
     * @return mixed
     */
    public function scopeMembershipStatus($query, $status, $at = null) {
        // Parsing the $at attribute
        if($at === null) {
            $at = Carbon::today()->toDateString();
        } elseif($at instanceof Carbon) {
            $at = $at->toDateString();
        }

        // Join the membership status with
        $query->leftJoinSub("
            SELECT DISTINCT ON(person_id) person_id, status as membership_status
            FROM membership_status_changes
            WHERE date <= '$at'::date
            ORDER BY person_id, date DESC
        ", 'last_membership_status', 'last_membership_status.person_id', '=' ,'persons.id');

        // Filtering the allowed statuses
        if(is_array($status)) {
            $query->whereIn('membership_status',$status);
            if(in_array(0, $status ) || in_array("0", $status)) {
                $query->orWhereNull('membership_status');
            }
        } else {
            if($status == 0) {
                $query->whereNull('membership_status');
            } else {
                $query->where('membership_status','=',$status);
            }
        }

        return $query;
    }

    /**
     * A scope that only gives the persons that were outsiders at the given $at date.
     *
     * If the parameter $at was not given, the current date will be used.
     *
     * @param $query
     * @param Carbon|string|null $at
     * @return mixed
     */
    public function scopeOutsider($query, $at = null) {
        return $this->scopeMembershipStatus($query, MembershipStatus::Outsider, $at);
    }

    /**
     * A scope that only gives the persons that were novices at the given $at date.
     *
     * If the parameter $at was not given, the current date will be used.
     *
     * @param $query
     * @param Carbon|string|null $at
     * @return mixed
     */
    public function scopeNovice($query, $at = null) {
        return $this->scopeMembershipStatus($query, MembershipStatus::Novice, $at);
    }

    /**
     * A scope that only gives the persons that were members at the given $at date.
     *
     * If the parameter $at was not given, the current date will be used.
     *
     * @param $query
     * @param Carbon|string|null $at
     * @return mixed
     */
    public function scopeMember($query, $at = null) {
        return $this->scopeMembershipStatus($query, MembershipStatus::Member, $at);
    }

    /**
     * A scope that only gives the persons that were former members at the given $at date.
     *
     * If the parameter $at was not given, the current date will be used.
     *
     * @param $query
     * @param Carbon|string|null $at
     * @return mixed
     */
    public function scopeFormerMember($query, $at = null) {
        return $this->scopeMembershipStatus($query, MembershipStatus::Novice, $at);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all the Memberships that this Person has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberships() {
        return $this->hasMany(Membership::class, 'person_id');
    }

    /**
     * Gives all the Membership status changes of this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function membershipStatusChanges() {
        return $this->hasMany(MembershipStatusChange::class, 'person_id');
    }


}