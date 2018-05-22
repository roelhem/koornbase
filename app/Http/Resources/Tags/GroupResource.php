<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 22-05-18
 * Time: 11:41
 */

namespace App\Http\Resources\Tags;


class GroupResource extends Resource
{

    public function toArray($request)
    {
        $res = parent::toArray($request);
        $res['member_name'] = $this->member_name;

        return $res;
    }

}