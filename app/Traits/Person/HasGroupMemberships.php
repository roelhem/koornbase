<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 09-05-18
 * Time: 16:24
 */

namespace App\Traits\Person;


use App\Enums\Chronology;
use App\Group;
use App\GroupMembership;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasGroupMemberships
 *
 *
 *
 * @package App\Traits\Person
 *
 * @property-read GroupMembership[] $groupMemberships
 * @property-read GroupMembership[] $futureGroupMemberships
 * @property-read GroupMembership[] $pastGroupMemberships
 * @property-read GroupMembership[] $currentGroupMemberships
 */
trait HasGroupMemberships
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Scope that only gives the Persons that are a member of the given $group.
     *
     * @param Builder $query
     * @param Group|string|integer $group
     * @param integer|null $chronology
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeGroupMemberOf($query, $group, $chronology = null, $at = null) {
        $group_id = $this->parseGroupId($group);
        return $query->whereHas('groupMemberships', function($query) use ($group_id, $chronology, $at) {
            if($chronology !== null) {
                $query->chronology($chronology, $at);
            }
            return $query->where('group_id', '=', $group_id);
        });
    }

    /**
     * Scope that only gives the Persons that are a member of the given $group in the future.
     *
     * @param Builder $query
     * @param Group|string|integer $group
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeFutureGroupMemberOf($query, $group, $at = null) {
        return $this->scopeGroupMemberOf($query, $group, Chronology::Future, $at);
    }

    /**
     * Scope that only gives the Persons that were a member of the given $group in the past.
     *
     * @param Builder $query
     * @param Group|string|integer $group
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopePastGroupMemberOf($query, $group, $at = null) {
        return $this->scopeGroupMemberOf($query, $group, Chronology::Past, $at);
    }

    /**
     * Scope that only gives the Persons that were a member of the given $group at the current time.
     *
     * @param Builder $query
     * @param Group|string|integer $group
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeCurrentGroupMemberOf($query, $group, $at = null) {
        return $this->scopeGroupMemberOf($query, $group, Chronology::Now, $at);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all the group memberships that belong to this Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupMemberships() {
        return $this->hasMany(GroupMembership::class, 'person_id');
    }

    /**
     * Gives all the group memberships that belong to this Person in the future.
     *
     * @param Carbon|null $at
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function futureGroupMemberships($at = null) {
        return $this->groupMemberships()->future($at);
    }

    /**
     * Gives all the group memberships that belong to this Person in the past.
     *
     * @param Carbon|null $at
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pastGroupMemberships($at = null) {
        return $this->groupMemberships()->past($at);
    }

    /**
     * Gives all the group memberships that belong to this Person at the current time.
     *
     * @param Carbon|null $at
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function currentGroupMemberships($at = null) {
        return $this->groupMemberships()->now($at);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the right group id from the given $group parameter.
     *
     * @param Group|string|integer $group
     * @return integer|null
     */
    private function parseGroupId($group) {
        if($group instanceof Group) {
            return $group->id;
        } elseif(is_integer($group)) {
            return $group;
        } elseif(ctype_digit($group)) {
            return intval($group);
        } elseif(is_string($group)) {
            return Group::findBySlug($group)->id;
        } else {
            return null;
        }
    }

}