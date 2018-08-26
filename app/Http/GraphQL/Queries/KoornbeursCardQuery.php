<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:11
 */

namespace App\Http\GraphQL\Queries;


use App\KoornbeursCard;

class KoornbeursCardQuery extends ModelViewQuery
{

    protected $modelClass = KoornbeursCard::class;

}