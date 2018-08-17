<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 03:33
 */

namespace App\GraphQL\Mutations\Crud;



use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class CreateCertificateMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createCertificate',
        'description' => 'Creates a new certificate entry in the database.'
    ];

    public function type()
    {
        return \GraphQL::type('Certificate');
    }

    public function args()
    {
        return [
            'category_id' => [
                'description' => 'The CertificateCategory of the new Certificate',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:certificate_categories'],
            ],
            'person_id' => [
                'description' => 'The `ID` of the Person for whom this Certificate is valid.',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['required','exists:persons'],
            ],
            'examination_at' => [
                'description' => 'The date on which the examination of the new Certificate took/will take place.',
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
                'description' => 'Some remarks regarding this new Certificate',
                'type' => Type::string(),
                'rules' => ['nullable','date'],
            ]
        ];
    }

    public function resolve($root, $args) {
        // TODO
    }

}