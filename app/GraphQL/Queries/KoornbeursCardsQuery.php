<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\GraphQL\Queries;

use App\KoornbeursCard;

class KoornbeursCardsQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'koornbeurs_cards'
    ];

    protected $typeName = 'KoornbeursCard';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return KoornbeursCard::query();
    }

}