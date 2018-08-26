<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 12:03
 */

namespace App\Http\GraphQL\Interfaces;


use App\Certificate;
use App\Contracts\OwnedByPerson;
use App\Debtor;
use App\Http\GraphQL\Types\PersonType;
use App\Http\GraphQL\Types\UserType;
use App\KoornbeursCard;
use App\Membership;
use App\PersonAddress;
use App\PersonEmailAddress;
use App\PersonPhoneNumber;
use GraphQL;
use Rebing\GraphQL\Support\InterfaceType;
use GraphQL\Type\Definition\Type;

class OwnedByPersonInterface extends InterfaceType
{

    protected $attributes = [
        'name' => 'OwnedByPerson',
        'description' => 'Interface for types that can be owned by a `Person` object.'
    ];

    /** @inheritdoc */
    public function fields()
    {
        return [
            'owner_id' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the person that owns this object.',
                'resolve' => function(OwnedByPerson $root) {
                    return $root->getOwnerId();
                }
            ],
            'owner' => [
                'type' => GraphQL::type('Person'),
                'description' => 'The person that owns this object.'
            ]
        ];
    }

    /** @inheritdoc */
    public function resolveType($root) {
        if ($root instanceof Certificate) {
            return GraphQL::type('Certificate');
        } elseif ($root instanceof Debtor) {
            return GraphQL::type('Debtor');
        } elseif ($root instanceof KoornbeursCard) {
            return GraphQL::type('KoornbeursCard');
        } elseif ($root instanceof Membership) {
            return GraphQL::type('Membership');
        } elseif ($root instanceof PersonAddress) {
            return GraphQL::type('PersonAddress');
        } elseif ($root instanceof PersonEmailAddress) {
            return GraphQL::type('PersonEmailAddress');
        } elseif ($root instanceof PersonPhoneNumber) {
            return GraphQL::type('PersonPhoneNumber');
        } elseif ($root instanceof PersonType) {
            return GraphQL::type('Person');
        } elseif ($root instanceof UserType) {
            return GraphQL::type('User');
        }
    }

}