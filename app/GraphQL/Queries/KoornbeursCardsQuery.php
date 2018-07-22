<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\GraphQL\Queries;

use App\KoornbeursCard;
use GraphQL\Type\Definition\Type;

class KoornbeursCardsQuery extends ModelListQuery
{

    protected $modelClass = KoornbeursCard::class;

    /** @inheritdoc */
    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [

            'version' => [
                'type' => Type::string(),
                'description' => 'Filters the KoornbeursCards with the (identical) provided version.'
            ],

            'active' => [
                'type' => Type::boolean(),
                'description' => 'Filters the KoornbeursCards that are active if the value is `true`, or inactive if the value is `false`.',
            ],

            'activeAt' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were/are active at the given moment.',
            ],

            'inactiveAt' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were/are inactive at the given moment.'
            ],

            'activatedBefore' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCarts that were activated before the provided moment.'
            ],

            'activatedAfter' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were activated after the provided moment.'
            ],

            'deactivatedBefore' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were deactivated before the provided moment.'
            ],

            'deactivatedAfter' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'Filters the KoornbeursCards that were deactivated after the provided moment.'
            ],

        ]);
    }

}