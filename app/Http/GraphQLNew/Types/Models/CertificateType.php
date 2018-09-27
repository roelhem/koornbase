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
                'type' => GraphQL::type('Person'),
                'importance' => 249,
            ],
            'category' => [
                'description' => 'The `CertificateCategory` of the `Certificate`. This object stores what it means to
                                  have this `Certificate`.',
                'type' => GraphQL::type('CertificateCategory'),
                'importance' => 248
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
                ],
                'importance' => 20,
            ],
            'examinationDate' => [
                'description' => 'The date on which the examination for this `Certificate` *took/will take* place.',
                'type' => GraphQL::type('Date'),
                'importance' => 3,
            ],
            'examinationPassed' => [
                'description' => 'Whether or not the exam for this `Certificate` was passed.',
                'type' => GraphQL::type('Boolean'),
                'importance' => 10,
            ],
            'validDate' => [
                'description' => 'The date on which `Certificate` will start to be valid. ',
                'type' => GraphQL::type('Date'),
                'importance' => 2,
            ],
            'expireDate' => [
                'description' => 'The date on the which `Certificate` expires.',
                'type' => GraphQL::type('Date'),
                'importance' => 1,
            ]
        ];
    }

    public function filters()
    {
        return [
            'isValid' => [
                'type' => GraphQL::type('Boolean'),
                'description' => 'Filters all the certificates that are valid if the value is true. If the value is false, only the invalid certificates are shown.'
            ],

            'validAt' => [
                'type' => GraphQL::type('Date'),
                'description' => 'Filters all the certificates that are valid at the given date.',
            ],

            'invalidAt' => [
                'type' => GraphQL::type('Date'),
                'description' => 'Filters all the certificates that are invalid at the given date.',
            ],

            'categoryId' => [
                'type' => GraphQL::type('ID'),
                'description' => 'Filters all the certificates that belong to the CertificateCategory with the given id.'
            ],

            'personId' => [
                'type' => GraphQL::type('ID'),
                'description' => 'Filters all the certificates that belong to the Person with the given id.'
            ],
        ];
    }

}