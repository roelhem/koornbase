<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:12
 */

namespace App\Http\GraphQL\Queries;


use App\Certificate;
use GraphQL\Type\Definition\Type;

class CertificatesQuery extends ModelListQuery
{

    protected $typeName = 'Certificate';

}