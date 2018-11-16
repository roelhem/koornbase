<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 17:43
 */

namespace App\Actions\Models\Create;

use Roelhem\GraphQL\Facades\GraphQL;

class CreateCertificateAction extends AbstractCreateAction
{

    protected $description = "Creates a new `Certificate` in the database.";

    /**
     * Method that returns the definition of the available arguments of this action.
     *
     * @return array
     */
    public function args()
    {
        return [
            'category_id' => [
                'description' => 'The CertificateCategory of the new Certificate',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:certificate_categories,id'],
            ],
            'person_id' => [
                'description' => 'The `ID` of the Person for whom this Certificate is valid.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:persons,id'],
            ],
            'examination_at' => [
                'description' => 'The date on which the examination of the new Certificate took/will take place.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'passed' => [
                'description' => 'If the person passed the exam for the Certificate.',
                'type' => GraphQL::type('Boolean'),
                'rules' => ['boolean'],
            ],
            'valid_at' => [
                'description' => 'The first date on which this Certificate is/will be valid.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:examination_at'],
            ],
            'expired_at' => [
                'description' => 'The first date on which this Certificate is/will be expired.',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:valid_at,examination_at'],
            ],
            'remarks' => [
                'description' => 'Some remarks regarding this new Certificate',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ]
        ];
    }
}