<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:05
 */

namespace App\GraphQL\Queries;


use App\Certificate;

class CertificateQuery extends ModelViewQuery
{

    protected $attributes = [
        'name' => 'certificate'
    ];

    protected $typeName = 'Certificate';

    public function query($args, $selectFields)
    {
        return Certificate::query();
    }

}