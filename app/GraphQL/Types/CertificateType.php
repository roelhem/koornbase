<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:48
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\Authorization\ViewableField;
use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\Relations\PersonField;
use App\GraphQL\Fields\Relations\PersonIdField;
use App\GraphQL\Fields\RemarksField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\Certificate;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;


class CertificateType extends GraphQLType
{

    protected $attributes = [
        'name' => 'Certificate',
        'model' => Certificate::class
    ];

    /** @inheritdoc */
    public function interfaces()
    {
        return [
            GraphQL::type('Model'),
            GraphQL::type('OwnedByPerson')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {
        $queryCallback = RbacQueryFilter::eagerLoadingContraintGraphQLClosure();
        $ownedByPersonInterface = GraphQL::type('OwnedByPerson');

        return [
            'id' => IdField::class,
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),


            'person_id' => PersonIdField::class,
            'person'    => PersonField::class,

            'category_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The `ID` of the CertificateCategory where this Certificate belongs to.'
            ],
            'category' => [
                'type' => GraphQL::type('CertificateCategory'),
                'description' => 'The CertificateCategory where this Certificate belongs to.',
                'query' => $queryCallback
            ],


            'examination_at' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The date on which the examination for this Certificate was/is.'
            ],
            'valid_at' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The date after which this Certificate is valid.'
            ],
            'expired_at' => [
                'type' => GraphQL::type('Date'),
                'description' => 'The date after which this Certificate is no longer valid.'
            ],
            'passed' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Gives if the examination for is Certificate was passed successfully.'
            ],


            'is_valid' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Gives if the certificate is valid at a certain moment.',
                'args' => [
                    'at' => [
                        'type' => GraphQL::type('Date'),
                        'description' => 'The date on which to check if this certificate is valid.'
                    ]
                ],
                'resolve' => function(Certificate $certificate, $args) {
                    $at = array_get($args, 'at', null);
                    return $certificate->isValid($at);
                },
                'selectable' => false,
                'always' => ['examination_at','valid_at','expired_at','passed'],
            ],

            'remarks' => RemarksField::class,

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,


            'viewable' => ViewableField::class,
        ];
    }

}