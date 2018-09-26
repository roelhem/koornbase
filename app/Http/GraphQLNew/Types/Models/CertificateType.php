<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 14:38
 */

namespace App\Http\GraphQLNew\Types\Models;


use App\Certificate;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class CertificateType extends ModelType
{

    public $modelClass = Certificate::class;

    public $name = 'Certificate';

    public $description = "The `Certificate`-type models the special qualifications, important for O.J.V. de Koornbeurs,
                           that a `Person` can acquire. It keeps track of *the examinations*, when the qualification
                           *starts*, and when is *expires*.";

    public function fields()
    {
        return [
            'person' => [
                'description' => 'The `Person` where this `Certificate` belongs to. (e.a. The `Person` that this 
                                 `Certificate` qualifies, if the `Certificate` is valid.)',
                'type' => GraphQL::type('Person!'),
            ],
            'category' => [
                'description' => 'The `CertificateCategory` of the `Certificate`. This object stores what it means to
                                  have this `Certificate`.',
                'type' => GraphQL::type('CertificateCategory!'),
            ],
            'examinationDate' => [
                'description' => 'The date on which the examination for this `Certificate` *took/will take* place.',
                'type' => GraphQL::type('Date'),
            ],
            'examinationPassed' => [
                'description' => 'Whether or not the exam for this `Certificate` was passed.',
                'type' => GraphQL::type('Boolean'),
            ],
            'validDate' => [
                'description' => 'The date on which `Certificate` will start to be valid. ',
                'type' => GraphQL::type('Date'),
            ],
            'isValid' => [
                'description' => 'Checks whether or not this `Certificate` is valid at a certain `Date`.',
                'type' => GraphQL::type('Boolean!'),
                'args' => [
                    'at' => [
                        'description' => 'The `Date` for which you want to check the validity of the `Certificate`.
                                          If this argument is omitted or set to `null`, the current `Date` will be
                                          used instead.',
                        'type' => GraphQL::type('Date'),
                    ]
                ]
            ],
            'expireDate' => [
                'description' => 'The date on the which `Certificate` expires.',
                'type' => GraphQL::type('Date'),
            ]
        ];
    }

}