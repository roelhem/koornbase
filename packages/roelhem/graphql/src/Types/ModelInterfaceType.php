<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 06:12
 */

namespace Roelhem\GraphQL\Types;


use Roelhem\GraphQL\Facades\GraphQL;

class ModelInterfaceType extends InterfaceType
{

    public $name = 'Model';

    public function __construct()
    {
        parent::__construct([
            'description' => 'This interface type is for all the types that represent a Model at the backend.'
        ]);
    }

    public function fields()
    {
        return [
            'id' => [
                'type' => GraphQL::type('ID!'),
                'description' => 'The primary-key of this `Model`. This value in combination with the type uniquely Identifies the object.',
                'importance' => 255,
            ],
            'modelInfo' => [
                'type' => GraphQL::type('ModelInfo'),
                'description' => "Provides some (server-side) information about the model.",
                'resolve' => function($source) { return $source; },
                'importance' => -255,
            ]
        ];
    }

}