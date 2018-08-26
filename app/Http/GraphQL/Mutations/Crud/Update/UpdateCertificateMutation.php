<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 17:11
 */

namespace App\Http\GraphQL\Mutations\Crud\Update;


use App\Certificate;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class UpdateCertificateMutation extends Mutation
{

    protected $attributes = [
        'name' => 'updateCertificate',
        'description' => 'Updates the values of a `Certificate`.'
    ];

    public function type()
    {
        return \GraphQL::type('Certificate');
    }

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `Certificate` that should be updated.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:certificates,id'],
            ],
            'examination_at' => [
                'description' => 'The date on which the examination of the Certificate took/will take place.',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'passed' => [
                'description' => 'If the person passed the exam for the Certificate.',
                'type' => Type::boolean(),
                'rules' => ['boolean'],
            ],
            'valid_at' => [
                'description' => 'The first date on which this Certificate is/will be valid.',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'expired_at' => [
                'description' => 'The first date on which this Certificate is/will be expired.',
                'type' => \GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'remarks' => [
                'description' => 'Some remarks regarding this new Certificate.',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ]
        ];
    }

    /**
     * @param $root
     * @param $args
     * @throws \Throwable
     * @return Certificate
     */
    public function resolve($root, $args)
    {
        // Find the Certificate model
        $id = array_get($args,'id');
        /** @var Certificate $certificate */
        $certificate = Certificate::findOrFail($id);

        // TODO: Validate the chronological order

        // Load the new values and save.
        $certificate->fill(array_except($args, 'id'));
        $certificate->saveOrFail();

        // Return the updated Certificate
        return $certificate;
    }
}