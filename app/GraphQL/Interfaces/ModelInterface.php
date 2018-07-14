<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 11:10
 */

namespace App\GraphQL\Interfaces;

use App\Certificate;
use App\CertificateCategory;
use App\Debtor;
use App\GraphQL\Types\PersonType;
use App\GraphQL\Types\UserAccountType;
use App\GraphQL\Types\UserType;
use App\Group;
use App\GroupCategory;
use App\GroupEmailAddress;
use App\KoornbeursCard;
use App\Membership;
use App\PersonAddress;
use App\PersonEmailAddress;
use App\PersonPhoneNumber;
use GraphQL;
use Illuminate\Database\Eloquent\Model;
use Rebing\GraphQL\Support\InterfaceType;
use GraphQL\Type\Definition\Type;

class ModelInterface extends InterfaceType
{

    protected $attributes = [
        'name' => 'Model',
        'description' => 'The `Model` interface type represents an entity that is stored in the central database.'
    ];

    /** @inheritdoc */
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The primary key of the model in the database table.'
            ]
        ];
    }

    /** @inheritdoc */
    public function resolveType(Model $root) {
        if($root instanceof CertificateCategory) {
            return GraphQL::type('CertificateCategory');
        } elseif ($root instanceof Certificate) {
            return GraphQL::type('Certificate');
        } elseif ($root instanceof Debtor) {
            return GraphQL::type('Debtor');
        } elseif ($root instanceof GroupCategory) {
            return GraphQL::type('GroupCategory');
        } elseif ($root instanceof Group) {
            return GraphQL::type('Group');
        } elseif ($root instanceof GroupEmailAddress) {
            return GraphQL::type('GroupEmailAddress');
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
        } elseif ($root instanceof UserAccountType) {
            return GraphQL::type('UserAccount');
        } elseif ($root instanceof UserType) {
            return GraphQL::type('User');
        }
    }

}