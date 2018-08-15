<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 09-05-18
 * Time: 13:44
 */

namespace App\Traits\Person;


use App\Membership;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use App\Helpers\MembershipStatusChange;
use App\Enums\MembershipStatus;
use Carbon\Carbon;

/**
 * Trait PersonHasMemberships
 * @package App\Traits
 *
 * @property-read MembershipStatus $membership_status
 * @property-read Carbon|null $membership_status_since
 *
 * @property-read Collection $memberships
 * @property-read Collection $membership_status_changes
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
        $at = \Parse::date($at, true);

        $result = $this->membershipStatusChanges()->where('date', '<=', $at)
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
     * @return MembershipStatus
     */
    public function getMembershipStatusAttribute()
    {
        $res = $this->getLastMembershipStatusChange();
        if($res === null) {
            return MembershipStatus::OUTSIDER();
        } else {
            return MembershipStatus::get($res->status);
        }
    }

    /**
     * Returns the last date on which the membership status changed.
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
        $at = \Parse::date($at, true)->toDateString();

        $status = MembershipStatus::by($status)->getValue();

        if(is_array($status)) {
            $status = collect($status)->map(function($status) {
                if($status instanceof MembershipStatus) {
                    return $status->getValue();
                } else {
                    return $status;
                }
            })->values()->all();
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
        return $this->scopeMembershipStatus($query, MembershipStatus::OUTSIDER, $at);
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
        return $this->scopeMembershipStatus($query, MembershipStatus::NOVICE, $at);
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
        return $this->scopeMembershipStatus($query, MembershipStatus::MEMBER, $at);
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
        return $this->scopeMembershipStatus($query, MembershipStatus::FORMER_MEMBER, $at);
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