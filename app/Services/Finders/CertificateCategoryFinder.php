<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-06-18
 * Time: 15:17
 */

namespace App\Services\Finders;


use App\CertificateCategory;

class CertificateCategoryFinder extends ModelByIdOrSlugFinder
{
    /**
     * @inheritdoc
     */
    public function modelClass(): string
    {
        return CertificateCategory::class;
    }

}