<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:12
 */

namespace App\GraphQL\Queries;


use App\CertificateCategory;

class CertificateCategoriesQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'certificate_categories'
    ];

    protected $typeName = 'CertificateCategory';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return CertificateCategory::query();
    }

}