<?php

namespace App\Http\Controllers\Api;

use App\Group;
use App\Http\Resources\Api\GroupResource;
use App\Services\Finders\GroupCategoryFinder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $query = Group::query();
        $query->with($this->getAskedRelations($request));

        return GroupResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GroupResource
     * @throws
     */
    public function store(Request $request, GroupCategoryFinder $groupCategoryFinder)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|finds:App\GroupCategory',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'member_name' => 'nullable|string|max:255',
        ]);

        $groupCategory = $groupCategoryFinder->find($validatedData['category']);
        $group = $groupCategory->groups()->create($validatedData);

        $group->load($this->getAskedRelations($request));

        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @param  Request     $request
     * @return GroupResource
     */
    public function show(Group $group, Request $request)
    {
        $group->load($this->getAskedRelations($request));
        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @throws
     */
    public function destroy(Group $group)
    {
        if($group->is_required) {
            abort(403, 'Deze groep kan niet worden verwijderd omdat de groep nodig is voor het goed functioneren van dit systeem.');
        } else {
            $group->delete();
        }
    }
}
