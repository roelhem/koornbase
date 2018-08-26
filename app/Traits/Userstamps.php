<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 21:05
 */

namespace App\Traits;

use App\User;
use Wildside\Userstamps\Userstamps as BaseUserStamps;

trait Userstamps
{
    use BaseUserStamps;

    protected function getUserClass()
    {
        return User::class;
    }
}