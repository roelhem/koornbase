<?php

namespace App\Http\Controllers\Api;

use App\GroupCategory;
use App\Http\Resources\Api\GroupCategoryResource;
use App\Http\Resources\Api\Resource;
use App\Services\Sorters\GroupCategorySorter;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupCategoryController extends Controller
{

    protected $modelClass = GroupCategory::class;
    protected $resourceClass = GroupCategoryResource::class;
    protected $sorterClass = GroupCategorySorter::class;


    public function index(Request $request) {
        $sorter = resolve($this->sorterClass);

        $query = GroupCategory::query();
        $query = $sorter->addList($query, $this->getSortList($request));
        $query->with($this->getAskedRelations($request));

        $paginate = $this->getPaginate($query, $request);

        return GroupCategoryResource::collection($paginate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Resource
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
     * @param  \App\GroupCategory $groupCategory
     * @param  Request $request
     * @return Resource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(GroupCategory $groupCategory, Request $request)
    {
        $this->authorize('view', $groupCategory);

        return $this->prepare($groupCategory, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupCategory  $groupCategory
     * @return Resource
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
