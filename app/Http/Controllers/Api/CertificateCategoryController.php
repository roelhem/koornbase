<?php

namespace App\Http\Controllers\Api;

use App\CertificateCategory;
use App\Http\Resources\Api\CertificateCategoryResource;
use App\Services\Finders\CertificateCategoryFinder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CertificateCategoryController extends Controller
{

    /**
     * Prepares a CertificateCategory to be send.
     *
     * @param CertificateCategory $category
     * @param Request $request
     * @return CertificateCategoryResource
     */
    protected function prepare(CertificateCategory $category, Request $request) {
        $category->load($this->getAskedRelations($request));
        return new CertificateCategoryResource($category);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $query = CertificateCategory::query();
        $query->with($this->getAskedRelations($request));

        return CertificateCategoryResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return CertificateCategoryResource
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
     * @return CertificateCategoryResource
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
     * @return CertificateCategoryResource
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
