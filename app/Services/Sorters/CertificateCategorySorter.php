<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 05:28
 */

namespace App\Services\Sorters;


class CertificateCategorySorter extends Sorter
{

    protected $columns = [
        'id','name','name_short','slug','description','default_expire_years','is_required','created_at','updated_at'
    ];

}