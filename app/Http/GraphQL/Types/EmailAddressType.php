<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 09:28
 */

namespace App\Http\GraphQL\Types;


use App\Types\EmailAddress;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class EmailAddressType extends ObjectType
{
    public $name = 'EmailAddress';

    public $description = 'The type `EmailAddress` represents a email-address, including all the contextual data
                    that is needed to properly send an E-mail to the address.';

    protected function fields()
    {
        return [
            'email' => [
                'description' => 'The e-mailadres itself. This is the only field that is strictly required when sending an E-mail.',
                'type' => GraphQL::type('Email!'),
                'importance' => 255,
            ],
            'name' => [
                'description' => 'The name of the recipient. (This is the string that is places at the start of the `<...>` part of a full E-mail address.)',
                'type' => GraphQL::type('String'),
                'importance' => 240,
            ],
            'combined' => [
                'description' => 'The name and email in one string combined, such that it is recognised by most E-mail applications',
                'type' => GraphQL::type('String'),
                'resolve' => function(EmailAddress $emailAddress) {
                    return $emailAddress->combined();
                }
            ],
            'link' => [
                'description' => 'A `mailto:...` link that, when clicked on by the user, will create a new E-mail to this E-mail address in the users E-mail application.',
                'type' => GraphQL::type('URL'),
                'args' => [
                    'encoded' => [
                        'description' => 'Set this argument to `true` to get the url-encoded string of the link.',
                        'type' => GraphQL::type('Boolean'),
                        'default' => false,
                    ]
                ],
                'resolve' => function(EmailAddress $emailAddress, $args) {
                    if(array_get($args, 'encoded',false)) {
                        return $emailAddress->encodedLink();
                    } else {
                        return $emailAddress->link();
                    }
                },
                'importance' => -1,
            ],
        ];
    }
}