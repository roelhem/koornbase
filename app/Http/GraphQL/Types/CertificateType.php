<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:48
 */

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Fields\Authorization\ViewableField;
use App\Http\GraphQL\Fields\IdField;
use App\Http\GraphQL\Fields\Relations\PersonField;
use App\Http\GraphQL\Fields\Relations\PersonIdField;
use App\Http\GraphQL\Fields\RemarksField;
use App\Http\GraphQL\Fields\Stamps\CreatedAtField;
use App\Http\GraphQL\Fields\Stamps\CreatedByField;
use App\Http\GraphQL\Fields\Stamps\CreatorField;
use App\Http\GraphQL\Fields\Stamps\EditorField;
use App\Http\GraphQL\Fields\Stamps\UpdatedAtField;
use App\Http\GraphQL\Fields\Stamps\UpdatedByField;
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

            'valid_since' => [
                'type' => GraphQL::type('Date'),
                'description' => 'Returns the first date on which this certificate was valid. If it\'s value is `null`, it means that this date is unknown (or hidden), but the contract can still be valid.',
                'selectable' => false,
                'always' => ['passed','valid_at','examination_at'],
            ],

            'valid_till' => [
                'type' => GraphQL::type('Date'),
                'description' => 'Returns the last date on which this certificate is valid. This field is just an alias of the `expired_at` field.',
                'selectable' => false,
                'always' => ['expired_at'],
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