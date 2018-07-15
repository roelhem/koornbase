<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 11:00
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\CountryCodeField;
use App\GraphQL\Fields\CountryField;
use App\GraphQL\Fields\Relations\PersonField;
use App\GraphQL\Fields\Relations\PersonIdField;
use App\GraphQL\Fields\RemarksField;
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
            GraphQL::type('PersonContactEntry')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {

        $ownedByPersonInterface = GraphQL::type('OwnedByPerson');
        $personContactEntryInterface = GraphQL::type('PersonContactEntry');

        return [
            GraphQL::type('Model')->getField('id'),
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),

            'person_id' => PersonIdField::class,
            'person' => PersonField::class,
            $personContactEntryInterface->getField('index'),
            $personContactEntryInterface->getField('label'),

            'country_code' => CountryCodeField::class,
            'country' => CountryField::class,

            // Address fields
            'administrative_area' => [
                'type' => Type::string(),
                'description' => 'the `administrative_area` field to store an address in the xAL-format.'
            ],
            'locality' => [
                'type' => Type::string(),
                'description' => 'the `locality` field to store an address in the xAL-format.'
            ],
            'dependent_locality' => [
                'type' => Type::string(),
                'description' => 'the `dependent_locality` field to store an address in the xAL-format.'
            ],
            'postal_code' => [
                'type' => Type::string(),
                'description' => 'the `postal_code` field to store an address in the xAL-format.'
            ],
            'sorting_code' => [
                'type' => Type::string(),
                'description' => 'the `sorting_code` field to store an address in the xAL-format.'
            ],
            'address_line_1' => [
                'type' => Type::string(),
                'description' => 'the `address_line1` field to store an address in the xAL-format.'
            ],
            'address_line_2' => [
                'type' => Type::string(),
                'description' => 'the `address_line2` field to store an address in the xAL-format.'
            ],
            'organisation' => [
                'type' => Type::string(),
                'description' => 'the `organisation` field to store an address in the xAL-format.'
            ],
            'locale' => [
                'type' => Type::string(),
                'description' => 'the `locale` field to store an address in the xAL-format.'
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
                },
                'selectable' => false,
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
                },
                'selectable' => false,
            ],

            'remarks' => RemarksField::class,

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
        ];
    }


}