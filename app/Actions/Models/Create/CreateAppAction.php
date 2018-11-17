<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:42
 */

namespace App\Actions\Models\Create;


use Roelhem\GraphQL\Facades\GraphQL;

class CreateAppAction extends AbstractCreateAction
{

    protected $description = "Creates a new `App`.";

    public function args()
    {
        return [
            'name' => [
                'description' => 'The name of the App.',
                'type' => GraphQL::type('String!'),
                'rules' => ['required','string','max:255'],
            ],
            'shortName' => [
                'alias' => 'name_short',
                'description' => 'A shorter name to describe the App.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63']
            ],
            'description' => [
                'description' => 'A long text description of what the App does.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string']
            ]
        ];
    }

}

/*

[
				"__schema",
				"types",
				95,
				"fields",
				20,
				"args",
				6,
				"type"
			]


 */