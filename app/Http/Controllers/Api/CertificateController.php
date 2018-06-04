<?php

namespace App\Http\Controllers\Api;

use App\Certificate;
use App\Http\Resources\Api\CertificateResource;
use App\Http\Resources\Api\Resource;
use App\Services\Finders\CertificateCategoryFinder;
use App\Services\Finders\PersonFinder;
use App\Services\Sorters\CertificateSorter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CertificateController extends Controller
{

    protected $modelClass = Certificate::class;
    protected $resourceClass = CertificateResource::class;
    protected $sorterClass = CertificateSorter::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param PersonFinder $personFinder
     * @param CertificateCategoryFinder $categoryFinder
     * @return Resource
     * @throws
     */
    public function store(Request $request, PersonFinder $personFinder, CertificateCategoryFinder $categoryFinder)
    {
        $validatedData = $request->validate([
            'person' => 'required|finds:App\Person',
            'category' => 'required|finds:App\CertificateCategory',
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

        $certificate = Certificate::create($inputData);

        return $this->prepare($certificate, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @param  \App\Certificate  $certificate
     * @return Resource
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
     * @return Resource
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
