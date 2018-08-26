<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:04
 */

namespace App\Http\GraphQL\Queries;


use App\CertificateCategory;

class CertificateCategoryQuery extends ModelViewQuery
{

    protected $modelClass = CertificateCategory::class;
    protected $slug = true;

}