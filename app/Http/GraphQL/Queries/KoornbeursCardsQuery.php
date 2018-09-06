<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\Http\GraphQL\Queries;

use App\KoornbeursCard;
use GraphQL\Type\Definition\Type;

class KoornbeursCardsQuery extends ModelListQuery
{

    protected $typeName = 'KoornbeursCard';

}