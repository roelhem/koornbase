<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 04:40
 */

namespace App\Traits\Person;

use App\Group;
use App\Pivots\PersonGroup;
use Illuminate\Database\Eloquent\Collection;


/**
 * Trait HasGroups
 * @package App\Traits\Person
 *
 * @property-read Collection $groups
 */
trait HasGroups
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER METHODS FOR CONVENIENCE --------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Adds one group to this person. You can specify the id, slug or give a Group instance.
     *
     * @param $group
     */
    public function addGroup($group) {
        $group_id = null;
        if($group instanceof Group) {
            $group_id = $group->id;
        } elseif(is_integer($group)) {
            $group_id = $group;
        } elseif(is_string($group)) {
            $group_id = Group::query()->where('slug', $group)->limit(1)->value('id');
        }

        if(is_integer($group_id)) {
            $this->groups()->attach($group_id);
        }
    }

    /**
     * Adds one or more groups to this person. You can specify the id, slug or give a Group instance.
     *
     * @param $groups
     */
    public function addGroups(...$groups) {
        foreach ($groups as $group) {
            if(is_array($group)) {
                $this->addGroups(...$group);
            } else {
                $this->addGroup($group);
            }
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all the groups that belong to this person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups() {
        return $this
            ->belongsToMany(Group::class, 'person_group','person_id','group_id')
            ->using(PersonGroup::class);
    }

}