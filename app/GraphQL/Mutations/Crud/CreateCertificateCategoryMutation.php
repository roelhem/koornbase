<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17-08-18
 * Time: 02:48
 */

namespace App\GraphQL\Mutations\Crud;


use App\CertificateCategory;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class CreateCertificateCategoryMutation extends Mutation
{

    protected $attributes = [
        'name' => 'createCertificateCategory',
        'description' => 'Creates a new CertificateCategory.'
    ];

    public function type()
    {
        return \GraphQL::type('CertificateCategory');
    }

    public function args()
    {
        return [
            'name' => [
                'description' => 'The name for the new CertificateCategory',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required','string','max:255','unique:certificate_categories'],
            ],
            'name_short' => [
                'description' => 'A short version of the name.',
                'type' => Type::string(),
                'rules' => ['nullable','string','max:63'],
            ],
            'description' => [
                'description' => 'A description of the category.',
                'type' => Type::string(),
                'rules' => ['nullable','string'],
            ],
            'default_expire_years' => [
                'description' => 'The default amount of years that a certificate of this category is valid.',
                'type' => Type::int(),
                'rules' => ['nullable','integer'],
            ]
        ];
    }

    public function resolve($root, $args)
    {
        return CertificateCategory::create($args);
    }

}