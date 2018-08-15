<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 14:41
 */

namespace App\Services\Parsers;


use Illuminate\Support\Facades\Facade;

class Parse extends Facade
{

    protected static function getFacadeAccessor()
    {
        return ParseService::class;
    }

}