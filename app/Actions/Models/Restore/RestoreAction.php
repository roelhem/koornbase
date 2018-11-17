<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:33
 */

namespace App\Actions\Models\Restore;


class RestoreAction extends AbstractRestoreAction
{
    public function __construct($type)
    {
        $this->type = $type;
    }

    public function name()
    {
        return 'restore'.$this->type()->name;
    }
}