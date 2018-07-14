<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 10:50
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\GroupEmailAddress;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GroupEmailAddressType extends GraphQLType
{

    protected $attributes = [
        'name' => 'GroupEmailAddress',
        'model' => GroupEmailAddress::class
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
        return [
            GraphQL::type('Model')->getField('id'),
            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
        ];
    }


}