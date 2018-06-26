<?php

namespace App\Http\Resources;

use App\Enums\MembershipStatus;
use App\Person;
use Illuminate\Http\Resources\Json\JsonResource;

class PeopleSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $person = $this->resource;

        if($person instanceof Person) {

            return [
                'id' => $person->id,
                'name' => [
                    'full' => $person->name,
                    'formal' => $person->name_formal,
                    'short' => $person->name_short,
                    'initials' => $person->name_initials,
                    'first' => $person->name_first,
                    'middle' => $person->name_middle,
                    'prefix' => $person->name_prefix,
                    'last' => $person->name_last,
                    'nickname' => $person->name_nickname,
                ],
                'birth_date' => $person->birth_date ? $person->birth_date->toDateString() : null,
                'avatar' => $person->avatar->toArray(),
                'membership' => $this->getMembershipStatus($person),
                'links' => [
                    'show' => route('people.person', ['person' => $person]),
                ],
                'groupMemberships' => $this->getGroupMemberships($person),
            ];
        }

        return parent::toArray($request);
    }

    protected function getMembershipStatus(Person $person) {
        $lastStatusChange = $person->getLastMembershipStatusChange();

        if ($lastStatusChange === null) {
            return [
                'status' => MembershipStatus::OUTSIDER(),
            ];
        } else {
            return [
                'status' => $lastStatusChange->status,
                'since' => $lastStatusChange->date->toDateString(),
            ];
        }
    }

    protected function getGroupMemberships(Person $person) {
        $res = [];
        foreach($person->groupMemberships as $groupMembership) {
            $res[] = [
                'id' => $groupMembership->id,
                'group_id' => $groupMembership->group_id,
                'start' => $groupMembership->start,
                'end' => $groupMembership->end
            ];
        }
        return $res;
    }
}
