<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 00:59
 */

namespace App\Actions\Models\Update;


use Roelhem\GraphQL\Facades\GraphQL;

class UpdateCertificateAction extends AbstractUpdateAction
{
    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `Certificate` that should be updated.',
                'type' => GraphQL::type('ID!'),
                'rules' => ['required','exists:certificates,id'],
            ],
            'examinationDate' => [
                'description' => 'The date on which the examination of the Certificate took/will take place.',
                'alias' => 'examination_at',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'examinationPassed' => [
                'description' => 'If the person passed the exam for the Certificate.',
                'type' => GraphQL::type('Boolean'),
                'rules' => ['boolean'],
            ],
            'validDate' => [
                'description' => 'The first date on which this Certificate is/will be valid.',
                'alias' => 'valid_at',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'expiredDate' => [
                'description' => 'The first date on which this Certificate is/will be expired.',
                'alias' => 'expired_at',
                'type' => GraphQL::type('Date'),
                'rules' => ['nullable','date'],
            ],
            'remarks' => [
                'description' => 'Some remarks regarding this new Certificate.',
                'type' => GraphQL::type('String'),
                'rules' => ['nullable','string'],
            ]
        ];
    }
}