<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 12:03
 */

namespace App\Http\GraphQL\Interfaces;


use App\Http\GraphQL\Fields\Relations\PersonField;
use App\Http\GraphQL\Fields\Relations\PersonIdField;
use App\Http\GraphQL\Fields\RemarksField;
use App\PersonAddress;
use App\PersonEmailAddress;
use App\PersonPhoneNumber;
use GraphQL;
use Rebing\GraphQL\Support\InterfaceType;
use GraphQL\Type\Definition\Type;

class PersonContactEntryInterface extends InterfaceType
{

    protected $attributes = [
        'name' => 'PersonContactEntry',
        'description' => 'Interface for contact entries of a person.'
    ];

    /** @inheritdoc */
    public function fields()
    {
        return [
            'person_id' => PersonIdField::class,
            'person' => PersonField::class,
            'index' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The index of this contact-entry. This number in combination with the person_id is unique.'
            ],
            'label' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The label of this contact-entry.'
            ],
            'remarks' => RemarksField::class,
        ];
    }

    /** @inheritdoc */
    public function resolveType($root) {
        if ($root instanceof PersonAddress) {
            return GraphQL::type('PersonAddress');
        } elseif ($root instanceof PersonEmailAddress) {
            return GraphQL::type('PersonEmailAddress');
        } elseif ($root instanceof PersonPhoneNumber) {
            return GraphQL::type('PersonPhoneNumber');
        }
    }

}