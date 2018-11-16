<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 22:02
 */

namespace App\Actions\Models\Update;


use Roelhem\GraphQL\Facades\GraphQL;

class UpdateCertificateCategoryAction extends AbstractUpdateAction
{

    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the CertificateCategory that you want to update.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:certificate_categories']
            ],
            'name' => [
                'description' => 'A new name for the CertificateCategory.',
                'type' => GraphQL::type('String'),
                'rules' => ['sometimes','required','string','max:255'],
            ],
            'name_short' => [
                'description' => 'A new short version of the name of the CertificateCategory.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'description' => [
                'description' => 'A new description for the CertificateCategory',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
            'default_expire_years' => [
                'description' => 'The mew default amount of years that a certificate of this category is valid.',
                'type' => GraphQL::type('Int'),
                'rules' => ['nullable','integer'],
            ]
        ];
    }
}