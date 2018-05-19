<?php

namespace App\Http\Controllers\People;

use App\Enums\Chronology;
use App\GroupCategory;
use App\Http\Resources\GroupCategorySearchResource;
use App\Http\Resources\PeopleSearchResource;
use App\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        return view('people.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request) {

        $membership_status = $request->input('membership_status');
        $groups = $request->input('groups');
        $sort = $request->input('sort');
        $sortOrder = mb_strtolower($request->input('sort_order'));
        $per_page = intval($request->input('per_page'));

        $query = Person::query();

        if($membership_status == '0' || !empty($membership_status)) {
            $query->membershipStatus($membership_status);
        }

        if(!empty($groups)) {
            if(is_string($groups)) {
                $groups = explode(',', $groups);
            }
            foreach ($groups as $group) {
                $query->groupMemberOf($group, Chronology::Now);
            }
        }


        if($sortOrder !== 'asc' && $sortOrder != 'desc') {
            $sortOrder = 'asc';
        }

        if(!empty($sort)) {
            switch ($sort) {
                case 'id':
                    $query->orderBy('id', $sortOrder);
                    break;
                case 'birth_date':
                    $query->orderBy('birth_date', $sortOrder);
                case 'name_last':
                    $query->orderBy('name_last', $sortOrder);
                case 'name_first':
                    $query->orderBy('name_first', $sortOrder);
                case 'name_nickname':
                    $query->orderBy('name_nickname', $sortOrder);
                    break;
                case 'membership_status':
                    $query->leftJoinSub("
                        SELECT DISTINCT ON(person_id) person_id, status, date
                        FROM membership_status_changes
                        WHERE date <= NOW()
                        ORDER BY person_id, date DESC
                    ", 'last_membership_status_sorting',
                        'last_membership_status_sorting.person_id', '=' ,'persons.id')
                        ->orderBy('last_membership_status_sorting.status', $sortOrder)
                        ->orderBy('last_membership_status_sorting.date', $sortOrder);
                    break;
            }
        }

        return PeopleSearchResource::collection($query->paginate($per_page));
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function group() {
        $query = GroupCategory::query();

        return GroupCategorySearchResource::collection($query->get());
    }

}
