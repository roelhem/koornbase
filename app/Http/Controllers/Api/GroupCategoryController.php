<?php

namespace App\Http\Controllers\Api;

use App\GroupCategory;
use App\Http\Resources\Api\GroupCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupCategoryController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $groupCategory->load($this->getAskedRelations($request));
        return new GroupCategoryResource($groupCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupCategory  $groupCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupCategory $groupCategory)
    {
        //
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
