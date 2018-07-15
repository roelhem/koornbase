<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:12
 */

namespace App\GraphQL\Queries;


use App\Certificate;

class CertificatesQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'certificates'
    ];

    protected $typeName = 'Certificate';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return Certificate::query();
    }

}