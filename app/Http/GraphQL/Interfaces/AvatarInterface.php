<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 13:10
 */

namespace App\Http\GraphQL\Interfaces;


use App\Types\Avatar;
use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\ResolveInfo;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\InterfaceType;

class AvatarInterface extends InterfaceType
{
    public $name = 'Avatar';

    public $description = "The `Avatar`-type represents the data that is needed to show a small icon in the UI
                           alongside model-data from various model-types.";

    public function fields()
    {
        return [
            'type' => [
                'description' => 'The type of the `Avatar`, which indicates the kind of model that the `Avatar` is
                                  based on, and the style in which the `Avatar` should be presented in the UI.',
                'type' => GraphQL::type('AvatarType'),
                'resolve' => function(Avatar $avatar) {
                    return $avatar->getType();
                },
                'importance' => -10,
            ],
            'image' => [
                'description' => 'Gives a link to an image that can be used in the Avatar. Returns `null` of no image
                                  was specified for this avatar.',
                'type' => GraphQL::type('URL'),
                'importance' => 100,
            ],
            'letters' => [
                'description' => 'Gives a short string that can be shown in the UI to the user as a placeholder if 
                                  no image was found for this avatar.',
                'type' => GraphQL::type('String'),
                'importance' => 90
            ],
        ];
    }

    /** @inheritdoc */
    public function resolveType($objectValue, $context, ResolveInfo $info)
    {
        if($objectValue instanceof Avatar) {
            return GraphQL::type($objectValue->getType()->getGraphQLTypeName());
        }

        throw new InvariantViolation("Unknown Avatar Type.");
    }
}