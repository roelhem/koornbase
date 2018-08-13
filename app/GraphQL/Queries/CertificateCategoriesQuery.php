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

    protected $modelClass = CertificateCategory::class;



    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [




        ]);
    }

}