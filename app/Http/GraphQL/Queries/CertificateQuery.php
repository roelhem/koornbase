<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:05
 */

namespace App\Http\GraphQL\Queries;


use App\Certificate;

class CertificateQuery extends ModelViewQuery
{

    protected $modelClass = Certificate::class;




}