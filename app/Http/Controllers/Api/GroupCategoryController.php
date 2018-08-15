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

    protected $eagerLoadForShow = ['groups'];


    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function store(Request $request)
    {

        $this->authorize('create', GroupCategory::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'style' => 'nullable|string|max:63'
        ]);

        $groupCategory = new GroupCategory($validatedData);
        $groupCategory->saveOrFail();

        return $this->createResource($groupCategory);
    }


    /**
     * @param Request $request
     * @param GroupCategory $groupCategory
     * @return \Illuminate\Http\Resources\Json\JsonResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function update(Request $request, GroupCategory $groupCategory)
    {
        $this->authorize('update', $groupCategory);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'style' => 'nullable|string|max:63'
        ]);

        $groupCategory->fill($validatedData);
        $groupCategory->saveOrFail();

        return $this->createResource($groupCategory);
    }
}
