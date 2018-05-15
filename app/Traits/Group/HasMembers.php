<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 10-05-18
 * Time: 00:33
 */

namespace App\Traits\Group;


use App\Group;
use App\GroupMembership;
use Carbon\Carbon;

/**
 * Trait HasMembers
 *
 * @package App\Traits\Group
 *
 * @property-read GroupMembership[] $memberships
 * @property-read GroupMembership[] $futureMemberships
 * @property-read GroupMembership[] $pastMemberships
 * @property-read GroupMembership[] $currentMemberships
 */
trait HasMembers
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the GroupMemberships that belong to this Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberships() {
        return $this->hasMany(GroupMembership::class, 'group_id');
    }

    /**
     * Gives the GroupMemberships that belong to this Group in the future.
     *
     * @param Carbon|null $at
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function futureMemberships($at = null) {
        return $this->memberships()->future($at);
    }

    /**
     * Gives the GroupMemberships that belong to this Group in the past.
     *
     * @param Carbon|null $at
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pastMemberships($at = null) {
        return $this->memberships()->past($at);
    }

    /**
     * Gives the GroupMemberships that belong to this Group at this moment.
     *
     * @param Carbon|null $at
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function currentMemberships($at = null) {
        return $this->memberships()->now($at);
    }

}