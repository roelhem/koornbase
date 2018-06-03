<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-06-18
 * Time: 18:06
 */

namespace App\Services\Finders;


use App\Certificate;

class CertificateFinder extends ModelByIdFinder
{

    public function modelClass(): string
    {
        return Certificate::class;
    }

}