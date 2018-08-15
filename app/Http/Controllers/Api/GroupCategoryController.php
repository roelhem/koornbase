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


    /*
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

        return $this->prepare($groupCategory, $request);
    }*/



    /*
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

        return $this->prepare($groupCategory, $request);
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @throws
     */
    public function destroy(Request $request)
    {
        /** @var GroupCategory $groupCategory */
        $groupCategory = $this->getModel($request);
        $this->authorize('delete', $groupCategory);

        if($groupCategory->is_required) {
            abort(403, 'Deze groep categorie kan niet worden verwijderd omdat deze groep categorie nodig is voor het goed functioneren van dit systeem.');
        } else {
            $groupCategory->delete();
        }
    }
}
