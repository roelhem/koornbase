<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 11:31
 */

namespace App\Http\GraphQLNew\Types;


use App\Enums\PersonNameFormat;
use App\Types\Name;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class PersonNameType extends ObjectType
{

    public $name = 'PersonName';

    public $description = 'This type represents a name of a `Person` in such a way that it can easily be formatted
                           in all the most common formats.';

    public function fields()
    {
        return [

            'format' => [
                'description' => 'A formatted name `String` in one of the pre-defined `PersonNameFormat`-typed styles.',
                'type' => GraphQL::type('String'),
                'args' => [
                    'format' => [
                        'description' => 'The `PersonNameFormat` that should be used to format this name.',
                        'type' => GraphQL::type('PersonNameFormat'),
                        'default' => PersonNameFormat::default(),
                    ],
                ],
                'resolve' => function(Name $name, $args) {
                    /** @var PersonNameFormat $format */
                    $format = array_get($args, 'format', PersonNameFormat::default());
                    return $format->format($name);
                },
                'importance' => 100,
            ],

            'initials' => [
                'description' => 'The initials, in capital letters and spaced with points. [**DUTCH:** initialen ]',
                'type' => GraphQL::type('String'),
                'alias' => 'name_initials',
                'importance' => 50,
            ],

            'first' => [
                'description' => 'The first-name. [**DUTCH:** voornaam/roepnaam. ]',
                'type' => GraphQL::type('String'),
                'alias' => 'name_first',
                'importance' => 40,
            ],

            'middle' => [
                'description' => 'The middle-name(s) or other extra names. Will only be used when the full name is required (for legal documents for instance.) [**DUTCH:** overige voornamen, extra namen, etc. ]',
                'type' => GraphQL::type('String'),
                'alias' => 'name_middle',
                'importance' => 30,
            ],

            'prefix' => [
                'description' => "The prefix of the last-name, like 'de','van der', etc. [**DUTCH:** tussenvoegsel ]",
                'type' => GraphQL::type('String'),
                'alias' => 'name_prefix',
                'importance' => 21,
            ],

            'last' => [
                'description' => "The last-name *[WITHOUT THE PREFIX!]*. Can be used for sorting on last name. [**DUTCH:** achternaam ]",
                'type' => GraphQL::type('String'),
                'alias' => 'name_last',
                'importance' => 20,
            ],

            'nickname' => [
                'description' => "The nickname that is frequently used by the members of O.J.V. de Koornbeurs. [**DUTCH:** bijnaam ]",
                'type' => GraphQL::type('String'),
                'alias' => 'name_nickname',
                'importance' => 10,
            ],

        ];
    }

}