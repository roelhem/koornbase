<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 21:43
 */

namespace App\Actions\Models\Delete;


class DeleteAction extends AbstractDeleteAction
{

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function name()
    {
        return 'delete'.$this->type()->name;
    }
}