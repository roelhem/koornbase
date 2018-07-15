<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:48
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\DescriptionField;
use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\IsRequiredField;
use App\GraphQL\Fields\NameField;
use App\GraphQL\Fields\NameShortField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\DeletedAtField;
use App\GraphQL\Fields\Stamps\DeletedByField;
use App\GraphQL\Fields\Stamps\DestroyerField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\CertificateCategory;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CertificateCategoryType extends GraphQLType
{

    protected $attributes = [
        'name' => 'CertificateCategory',
        'model' => CertificateCategory::class
    ];

    /** @inheritdoc */
    public function interfaces()
    {
        return [
            GraphQL::type('Model'),
            GraphQL::type('Sluggable')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {

        return [
            'id' => IdField::class,
            GraphQL::type('Sluggable')->getField('slug'),

            'name'        => NameField::class,
            'name_short'  => NameShortField::class,
            'description' => DescriptionField::class,

            'default_expire_years' => [
                'type' => Type::int(),
                'description' => 'The default amount of years that a certificate of this category is valid.'
            ],

            'is_required' => IsRequiredField::class,

            'certificates' => [
                'type' => Type::listOf(GraphQL::type('Certificate')),
                'description' => 'A list of all the certificates that belong to this CertificateCategory.'
            ],

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
            'deleted_at' => DeletedAtField::class,
            'deleted_by' => DeletedByField::class,
            'destroyer'  => DestroyerField::class
        ];
    }

}