<?php

namespace App\Http\Controllers\Api;

use App\Certificate;
use App\Contracts\Finders\FinderCollection;
use App\Http\Resources\Api\CertificateResource;
use App\Services\Sorters\CertificateSorter;
use Illuminate\Http\Request;

class CertificateController extends Controller
{

    protected $modelClass = Certificate::class;
    protected $resourceClass = CertificateResource::class;
    protected $sorterClass = CertificateSorter::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param FinderCollection $finders
     * @return Resource
     * @throws
     */
    public function store(Request $request, FinderCollection $finders)
    {
        $this->authorize('create',Certificate::class);

        $validatedData = $request->validate([
            'person' => 'required|finds:person',
            'category' => 'required|finds:certificate_category',
            'passed' => 'boolean',
            'examination_at' => 'nullable|date',
            'valid_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'remarks' => 'nullable|string'
        ]);

        $person = $finders->find($validatedData['person'], 'person');
        $category = $finders->find($validatedData['category'], 'certificate_category');

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
     * @throws
     */
    public function show(Request $request, Certificate $certificate)
    {
        $this->authorize('view', $certificate);

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
        $this->authorize('update',$certificate);

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
        $this->authorize('delete', $certificate);

        $certificate->delete();
    }
}
