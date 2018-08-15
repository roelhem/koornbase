<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 13:10
 */

namespace App\Http\Controllers\Api;


use App\Certificate;
use App\CertificateCategory;
use App\Contracts\Finders\FinderCollection;
use App\Http\Requests\Api\CertificateStoreRequest;
use App\Http\Requests\Api\CertificateUpdateRequest;

class CertificateController extends Controller
{
    protected $eagerLoadForShow = ['person','category'];


    /**
     * Actie die een nieuw certificaat aan de database toevoegd.
     *
     * @param CertificateStoreRequest $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     * @throws \Throwable
     */
    public function store(CertificateStoreRequest $request, FinderCollection $finders)
    {
        $data = $request->validated();

        $values = array_except($data, ['person','category']);
        $values['person_id'] = $finders->find(array_get($data, 'person'), 'person')->id;

        /** @var CertificateCategory $certificate */
        $category = $finders->find(array_get($data,'category'), 'certificate_category');
        $certificate = $category->certificates()->create($values);

        $certificate->load($this->createEagerLoadDefinition($this->eagerLoadForShow));

        return $this->createResource($certificate);
    }

    /**
     * Actie die de gegevens van een Certificaat bijwerkt.
     *
     * @param CertificateUpdateRequest $request
     * @param Certificate $certificate
     * @return \Illuminate\Http\Resources\Json\JsonResource
     * @throws \Throwable
     */
    public function update(CertificateUpdateRequest $request, Certificate $certificate)
    {
        $certificate->fill($request->validated());
        $certificate->saveOrFail();

        $certificate->load($this->createEagerLoadDefinition($this->eagerLoadForShow));

        return $this->createResource($certificate);
    }
}