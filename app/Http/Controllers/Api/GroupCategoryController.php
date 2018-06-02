<?php

namespace App\Http\Controllers\Api;

use App\GroupCategory;
use App\Http\Resources\Api\GroupCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupCategoryController extends Controller
{

    /**
     * Prepares a group-category to be send by an action.
     *
     * @param GroupCategory $category
     * @param Request $request
     * @return GroupCategoryResource
     */
    protected function prepare(GroupCategory $category, Request $request) {
        $category->load($this->getAskedRelations($request));
        return new GroupCategoryResource($category);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $query = GroupCategory::query();
        $query->with($this->getAskedRelations($request));

        return GroupCategoryResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GroupCategoryResource
     * @throws
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'style' => 'nullable|string|max:63'
        ]);

        $groupCategory = new GroupCategory($validatedData);
        $groupCategory->saveOrFail();

        return $this->prepare($groupCategory, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GroupCategory  $groupCategory
     * @param  Request             $request
     * @return GroupCategoryResource
     */
    public function show(GroupCategory $groupCategory, Request $request)
    {
        return $this->prepare($groupCategory, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupCategory  $groupCategory
     * @return GroupCategoryResource
     * @throws
     */
    public function update(Request $request, GroupCategory $groupCategory)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'style' => 'nullable|string|max:63'
        ]);

        $groupCategory->fill($validatedData);
        $groupCategory->saveOrFail();

        return $this->prepare($groupCategory, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroupCategory  $groupCategory
     * @throws
     */
    public function destroy(GroupCategory $groupCategory)
    {
        if($groupCategory->is_required) {
            abort(403, 'Deze groep categorie kan niet worden verwijderd omdat deze groep categorie nodig is voor het goed functioneren van dit systeem.');
        } else {
            $groupCategory->delete();
        }
    }
}
