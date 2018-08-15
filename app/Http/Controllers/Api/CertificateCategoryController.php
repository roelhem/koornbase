<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 13:10
 */

namespace App\Http\Controllers\Api;


use App\CertificateCategory;
use Illuminate\Http\Request;

class CertificateCategoryController extends Controller
{

    protected $eagerLoadForShow = ['certificates'];


    /**
     * Action that creates a new Certificate Category in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->authorize('create',CertificateCategory::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'default_expire_years' => 'nullable|integer'
        ]);

        $certificateCategory = new CertificateCategory($validatedData);
        $certificateCategory->saveOrFail();

        return $this->createResource($certificateCategory);
    }

    /**
     * Action that updates the values of a CertificateCategory in the database.
     *
     * @param Request $request
     * @param CertificateCategory $certificateCategory
     * @return \Illuminate\Http\Resources\Json\JsonResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function update(Request $request, CertificateCategory $certificateCategory)
    {
        $this->authorize('update', $certificateCategory);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'name_short' => 'nullable|string|max:63',
            'description' => 'nullable|string',
            'default_expire_years' => 'nullable|integer'
        ]);

        $certificateCategory->fill($validatedData);
        $certificateCategory->saveOrFail();

        return $this->createResource($certificateCategory);
    }
}