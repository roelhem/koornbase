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
            'categoryId' => [
                'description' => 'The CertificateCategory of the new Certificate',
                'alias' => 'category_id',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:certificate_categories,id'],
            ],
            'personId' => [
                'description' => 'The `ID` of the Person for whom this Certificate is valid.',
                'alias' => 'person_id',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:persons,id'],
            ],
            'examinationDate' => [
                'description' => 'The date on which the examination of the new Certificate took/will take place.',
                'alias' => 'examination_at',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'examinationPassed' => [
                'description' => 'If the person passed the exam for the Certificate.',
                'alias' => 'passed',
                'type' => GraphQL::type('Boolean'),
                'rules' => ['boolean'],
            ],
            'validDate' => [
                'description' => 'The first date on which this Certificate is/will be valid.',
                'alias' => 'valid_at',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date','after_or_equal_fields:examination_at'],
            ],
            'expireDate' => [
                'description' => 'The first date on which this Certificate is/will be expired.',
                'alias' => 'expire_at',
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