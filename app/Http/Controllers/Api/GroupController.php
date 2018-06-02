<?php

namespace App\Http\Controllers\Api;

use App\Group;
use App\Http\Resources\Api\GroupResource;
use App\Services\Finders\GroupCategoryFinder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    /**
     * Prepares a group to be send by an action.
     *
     * @param Model $group
     * @param Request $request
     * @return GroupResource
     */
    protected function prepare($group, Request $request) {
        $group->load($this->getAskedRelations($request));
        return new GroupResource($group);
    }

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

        return $this->prepare($group, $request);
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
        return $this->prepare($group, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @param  GroupCategoryFinder $categoryFinder
     * @return GroupResource
     * @throws
     */
    public function update(Request $request, Group $group, GroupCategoryFinder $categoryFinder)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|finds:App\GroupCategory',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'member_name' => 'nullable|string|max:255',
        ]);

        $group->fill($validatedData);

        if(array_has($validatedData, 'category')) {
            $category = $categoryFinder->find($validatedData['category']);
            $group->category()->associate($category);
        }

        $group->saveOrFail();

        return $this->prepare($group, $request);
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
