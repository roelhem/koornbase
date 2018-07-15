<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:11
 */

namespace App\GraphQL\Queries;


use App\KoornbeursCard;

class KoornbeursCardQuery extends ModelViewQuery
{

    protected $attributes = [
        'name' => 'groupCategory'
    ];

    protected $typeName = 'GroupCategory';

    public function query($args, $selectFields)
    {
        return KoornbeursCard::query();
    }

}