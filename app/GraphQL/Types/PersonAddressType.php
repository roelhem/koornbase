<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 11:00
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\PersonAddress;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PersonAddressType extends GraphQLType
{

    protected $attributes = [
        'name' => 'PersonAddress',
        'model' => PersonAddress::class
    ];

    /** @inheritdoc */
    public function interfaces()
    {
        return [
            GraphQL::type('Model'),
            GraphQL::type('OwnedByPerson'),
            GraphQL::type('PersonContactEntry'),
            GraphQL::type('BelongsToCountry')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {

        $ownedByPersonInterface = GraphQL::type('OwnedByPerson');
        $personContactEntryInterface = GraphQL::type('PersonContactEntry');
        $belongsToCountryInterface = GraphQL::type('BelongsToCountry');

        return [
            GraphQL::type('Model')->getField('id'),
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),

            $personContactEntryInterface->getField('person_id'),
            $personContactEntryInterface->getField('person'),
            $personContactEntryInterface->getField('index'),
            $personContactEntryInterface->getField('label'),

            $belongsToCountryInterface->getField('country_code'),
            $belongsToCountryInterface->getField('country'),

            // Address fields
            'administrative_area' => [
                'type' => Type::string(),
            ],
            'locality' => [
                'type' => Type::string(),
            ],
            'dependent_locality' => [
                'type' => Type::string(),
            ],
            'postal_code' => [
                'type' => Type::string(),
            ],
            'sorting_code' => [
                'type' => Type::string(),
            ],
            'address_line_1' => [
                'type' => Type::string(),
            ],
            'address_line_2' => [
                'type' => Type::string(),
            ],
            'organisation' => [
                'type' => Type::string(),
            ],
            'locale' => [
                'type' => Type::string(),
            ],

            // Formatting
            'format' => [
                'type' => Type::string(),
                'args' => [
                    'locale' => [
                        'type' => Type::string(),
                        'defaultValue' => 'nl'
                    ],
                    'html' => [
                        'type' => Type::boolean(),
                        'defaultValue' => false
                    ],
                    'html_tag' => [
                        'type' => Type::string(),
                        'defaultValue' => 'div',
                    ],
                    'html_attributes' => [
                        'type' => GraphQL::type('HtmlAttributes'),
                        'defaultValue' => [
                            'translate' => 'no',
                            'class' => 'generated_address_html'
                        ]
                    ]
                ],
                'resolve' => function(PersonAddress $root, $args) {
                    return $root->format($args);
                }
            ],

            'postal_label' => [
                'type' => Type::string(),
                'args' => [
                    'locale' => [
                        'type' => Type::string(),
                        'defaultValue' => 'nl'
                    ],
                    'html' => [
                        'type' => Type::boolean(),
                        'defaultValue' => false,
                    ],
                    'html_tag' => [
                        'type' => Type::string(),
                        'defaultValue' => 'p'
                    ],
                    'html_attributes' => [
                        'type' => GraphQL::type('HtmlAttributes'),
                        'defaultValue' => [
                            'translate' => 'no',
                            'class' => 'generated_address_postal_label'
                        ]
                    ],
                    'origin_country' => [
                        'type' => Type::string(),
                        'defaultValue' => 'NL'
                    ]
                ],
                'resolve' => function(PersonAddress $root, $args) {
                    return $root->postalLabel($args);
                }
            ],

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
        ];
    }


}