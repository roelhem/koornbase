<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:42
 */

namespace App\Actions\Models\Create;

use Roelhem\GraphQL\Facades\GraphQL;

class CreateCertificateCategoryAction extends AbstractCreateAction
{

    protected $description = 'Creates a new `CertificateCategory`.';

    /**
     * Method that returns the definition of the available arguments of this action.
     *
     * @return array
     */
    public function args()
    {
        return [
            'name' => [
                'description' => 'The name for the new CertificateCategory',
                'type' => GraphQL::type('String!'),
                'rules' => ['required','string','max:255','unique:certificate_categories'],
            ],
            'name_short' => [
                'description' => 'A short version of the name.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string','max:63'],
            ],
            'description' => [
                'description' => 'A description of the category.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ],
            'default_expire_years' => [
                'description' => 'The default amount of years that a certificate of this category is valid.',
                'type' => GraphQL::type('Int'),
                'rules' => ['nullable','integer'],
            ]
        ];
    }
}