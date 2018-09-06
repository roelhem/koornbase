<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 16:14
 */

namespace App\Http\GraphQL\Interfaces;


use App\Http\GraphQL\Fields\Stamps\CreatedAtField;
use App\Http\GraphQL\Fields\Stamps\CreatedByField;
use App\Http\GraphQL\Fields\Stamps\CreatorField;
use App\Http\GraphQL\Fields\Stamps\EditorField;
use App\Http\GraphQL\Fields\Stamps\UpdatedAtField;
use App\Http\GraphQL\Fields\Stamps\UpdatedByField;
use Rebing\GraphQL\Support\InterfaceType;

class StampedModelInterface extends InterfaceType
{

    protected $attributes = [
        'name' => 'StampedModel',
        'description' => 'This Interface represents the Models that always store the creation and last updated dates and actors.'
    ];

    public function fields()
    {
        return [
            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
        ];
    }

}