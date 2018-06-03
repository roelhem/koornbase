<?php

namespace App\Http\Controllers\Api;

use App\Certificate;
use App\Http\Resources\Api\CertificateResource;
use App\Services\Finders\CertificateCategoryFinder;
use App\Services\Finders\PersonFinder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CertificateController extends Controller
{

    /**
     * Prepares a Certificate to be send.
     *
     * @param Certificate $certificate
     * @param Request $request
     * @return CertificateResource
     */
    protected function prepare(Certificate $certificate, Request $request) {
        $certificate->load($this->getAskedRelations($request));
        return new CertificateResource($certificate);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $query = Certificate::query();
        $query->with($this->getAskedRelations($request));

        return CertificateResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param PersonFinder $personFinder
     * @param CertificateCategoryFinder $categoryFinder
     * @return CertificateResource
     * @throws
     */
    public function store(Request $request, PersonFinder $personFinder, CertificateCategoryFinder $categoryFinder)
    {
        $validatedData = $request->validate([
            'person' => 'required|finds:App\Person',
            'category' => 'required|finds:App\Category',
            'passed' => 'boolean',
            'examination_at' => 'nullable|date',
            'valid_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'remarks' => 'nullable|string'
        ]);

        $person = $personFinder->find($validatedData['person']);
        $category = $categoryFinder->find($validatedData['category']);

        $inputData = array_except($validatedData, ['person','category']);
        $inputData['person_id'] = $person->id;
        $inputData['category_id'] = $category->id;

        $certificate = Certificate::create($validatedData);

        return $this->prepare($certificate, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @param  \App\Certificate  $certificate
     * @return CertificateResource
     */
    public function show(Request $request, Certificate $certificate)
    {
        return $this->prepare($certificate, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Certificate  $certificate
     * @return CertificateResource
     * @throws
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validatedData = $request->validate([
            'passed' => 'boolean',
            'examination_at' => 'nullable|date',
            'valid_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'remarks' => 'nullable|string'
        ]);

        $certificate->fill($validatedData);
        $certificate->saveOrFail();

        return $this->prepare($certificate, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Certificate  $certificate
     * @throws
     */
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
    }
}
