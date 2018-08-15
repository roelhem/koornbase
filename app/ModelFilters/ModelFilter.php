<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 05-07-18
 * Time: 10:00
 */

namespace App\ModelFilters;

use EloquentFilter\ModelFilter as Filter;


abstract class ModelFilter extends Filter
{


    public function createdBefore($timestamp) {
        $this->where('created_at', '<', \Parse::date($timestamp));
    }

    public function createdAfter($timestamp) {
        $this->where('created_at','>=', \Parse::date($timestamp));
    }

    public function updatedBefore($timestamp) {
        $this->where('updated_at','<', \Parse::date($timestamp));
    }

    public function updatedAfter($timestamp) {
        $this->where('updated_at', '>=', \Parse::date($timestamp));
    }

}