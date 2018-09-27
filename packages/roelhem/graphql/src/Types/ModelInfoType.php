<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 07:08
 */

namespace Roelhem\GraphQL\Types;


use Roelhem\GraphQL\Facades\GraphQL;

class ModelInfoType extends ObjectType
{

    public $name = "ModelInfo";

    public $description = "The `ModelInfo` Object-type provides some information about the status of this model.";

    /**
     * Returns the definitions of the fields of this Type.
     *
     * @return array
     */
    protected function fields()
    {
        return [
            'createdAt' => [
                'description' => 'The moment on which the model was created.',
                'type' => GraphQL::type('DateTime'),
                'alias' => 'created_at',
            ],
            'createdBy' => [
                'description' => 'The `User` that created this model.',
                'type' => GraphQL::type('User'),
                'alias' => 'creator',
            ],
            'updatedAt' => [
                'description' => 'The last time that the values of this model were edited.',
                'type' => GraphQL::type('DateTime'),
                'alias' => 'updated_at',
            ],
            'updatedBy' => [
                'description' => 'The last `User` that updated the values of this model.',
                'type' => GraphQL::type('User'),
                'alias' => 'editor',
            ],
            'deletedAt' => [
                'description' => 'The moment when this model was (soft)-deleted.',
                'type' => GraphQL::type('DateTime'),
                'alias' => 'deleted_at',
            ],
            'deletedBy' => [
                'description' => 'The `User` that (soft)-deleted this model.',
                'type' => GraphQL::type('User'),
                'alias' => 'destroyer'
            ]
        ];
    }
}