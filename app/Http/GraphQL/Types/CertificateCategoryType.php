<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:48
 */

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Fields\Authorization\ViewableField;
use App\Http\GraphQL\Fields\DescriptionField;
use App\Http\GraphQL\Fields\IdField;
use App\Http\GraphQL\Fields\IsRequiredField;
use App\Http\GraphQL\Fields\NameField;
use App\Http\GraphQL\Fields\NameShortField;
use App\Http\GraphQL\Fields\SlugField;
use App\Http\GraphQL\Fields\Stamps\CreatedAtField;
use App\Http\GraphQL\Fields\Stamps\CreatedByField;
use App\Http\GraphQL\Fields\Stamps\CreatorField;
use App\Http\GraphQL\Fields\Stamps\DeletedAtField;
use App\Http\GraphQL\Fields\Stamps\DeletedByField;
use App\Http\GraphQL\Fields\Stamps\DestroyerField;
use App\Http\GraphQL\Fields\Stamps\EditorField;
use App\Http\GraphQL\Fields\Stamps\UpdatedAtField;
use App\Http\GraphQL\Fields\Stamps\UpdatedByField;
use App\CertificateCategory;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

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
            GraphQL::type('Model')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {

        $queryCallback = RbacQueryFilter::eagerLoadingContraintGraphQLClosure();

        return [
            'id' => IdField::class,
            'slug' => SlugField::class,

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
                'description' => 'A list of all the certificates that belong to this CertificateCategory.',
                'query' => $queryCallback
            ],

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
            'deleted_at' => DeletedAtField::class,
            'deleted_by' => DeletedByField::class,
            'destroyer'  => DestroyerField::class,

            'viewable' => ViewableField::class
        ];
    }

}