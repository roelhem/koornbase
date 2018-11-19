<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 07:54
 */

namespace App\Http\GraphQL\Interfaces;


use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\InterfaceType;

class PersonContactEntryInterface extends InterfaceType
{

    public $name = 'PersonContactEntry';

    public function fields()
    {
        return [
            'index' => [
                'type' => GraphQL::type('Int'),
                'description' => 'The position of this `PersonContractEntry` in relation to the entries of the same type and the same Person.',
                'importance' => 253,
            ],
            'label' => [
                'type' => GraphQL::type('String'),
                'description' => 'A string that (uniquely) identifies the relation between the entry and the `Person` of the entry.',
                'importance' => 250,
            ],
            'person' => [
                'type' => GraphQL::type('Person'),
                'description' => 'The `Person` to which this contract-entry belongs to.',
                'importance' => 240,
            ],
            'remarks' => [
                'type' => GraphQL::type('String'),
                'description' => 'Some optional remarks about this contact-entry.',
                'importance' => -200,
            ]
        ];
    }

}