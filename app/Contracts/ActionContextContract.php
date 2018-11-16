<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-10-18
 * Time: 07:35
 */

namespace App\Contracts;


interface ActionContextContract
{
    public function can($ability, $attributes = []);
}