<?php

namespace App\Http\Controllers\Api;

use App\CertificateCategory;
use App\Http\Resources\Api\CertificateCategoryResource;
use App\Services\Finders\CertificateCategoryFinder;
use App\Services\Sorters\CertificateCategorySorter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CertificateCategoryController extends Controller
{

    protected $modelClass = CertificateCategory::class;
    protected $sorterClass = CertificateCategorySorter::class;
    protected $resourceClass = CertificateCategoryResource::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Resource
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'default_expire_years' => 'nullable|integer|min:0'
        ]);

        $category = CertificateCategory::create($validatedData);

        return $this->prepare($category, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @param  \App\CertificateCategory  $category
     * @return Resource
     */
    public function show(Request $request, CertificateCategory $category)
    {
        return $this->prepare($category, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CertificateCategory  $category
     * @return Resource
     * @throws
     */
    public function update(Request $request, CertificateCategory $category)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|max:255',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'default_expire_years' => 'nullable|integer|min:0'
        ]);

        $category->fill($validatedData);
        $category->saveOrFail();

        return $this->prepare($category, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @param  \App\CertificateCategory  $category
     * @throws
     */
    public function destroy(Request $request, CertificateCategory $category)
    {
        if($category->is_required) {
            abort(403, 'Dit certificaat is belangrijk voor het werken van het systeem.');
        } else {
            $category->delete();
        }
    }
}
