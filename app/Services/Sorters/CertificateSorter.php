<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 05:29
 */

namespace App\Services\Sorters;


class CertificateSorter extends Sorter
{

    protected $columns = [
        'id','person_id','category_id','examination_at','passed','valid_at','expired_at','created_at','updated_at'
    ];

}