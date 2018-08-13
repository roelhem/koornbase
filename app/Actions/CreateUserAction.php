<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-07-18
 * Time: 05:54
 */

namespace App\Actions;


class CreateUserAction
{


    public function arguments()
    {
        return [
            'name'      => [],
            'email'     => [],
            'password'  => [],
            'person_id' => []
        ];
    }

    public function prepare()
    {

    }

    public function perform()
    {

    }


}