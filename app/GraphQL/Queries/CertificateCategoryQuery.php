<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:04
 */

namespace App\GraphQL\Queries;


use App\CertificateCategory;

class CertificateCategoryQuery extends ModelViewQuery
{

    protected $attributes = [
        'name' => 'certificateCategory'
    ];

    protected $typeName = 'CertificateCategory';
    protected $slug = true;


    public function query($args, $selectFields)
    {
        return CertificateCategory::query();
    }

}