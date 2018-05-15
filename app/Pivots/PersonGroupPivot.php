<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 09-05-18
 * Time: 12:06
 */

namespace App\Pivots;

use App\Group;
use App\Person;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Enums\GroupMembershipStatus;

/**
 * Class PersonGroupPivot
 * @package App\Pivots
 *
 * @property integer|null $id
 * @property integer|null $person_id
 * @property integer|null $group_id
 * @property Carbon|null $start
 * @property Carbon|null $end
 */
class PersonGroupPivot extends Pivot
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $dates = ['start', 'end'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the status of the current group membership.
     *
     * If no parameter $at is given, the current date will be used. This will return an integer as defined
     * in the GroupMembershipStatus Enum.
     *
     * @param Carbon|null $at
     * @return int
     */
    public function getStatus($at = null) {
        if($this->start === null && $this->end === null) {
            return GroupMembershipStatus::Unknown;
        }

        if($at === null) {
            $at = Carbon::now();
        } elseif(is_string($at)) {
            $at = Carbon::parse($at);
        }

        if($this->start > $at) {
            return GroupMembershipStatus::UpcomingMember;
        } else {
            if($this->end === null || $this->end > $at) {
                return GroupMembershipStatus::Member;
            } else {
                return GroupMembershipStatus::FormerMember;
            }
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the current status of the current group membership.
     *
     * @return int
     */
    public function getStatusAttribute() {
        return $this->getStatus();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group() {
        return $this->belongsTo(Group::class, 'group_id');
    }

}