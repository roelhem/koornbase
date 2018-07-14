<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 11:45
 */

namespace App\GraphQL\Interfaces;



use App\CertificateCategory;
use App\Group;
use App\GroupCategory;
use GraphQL;
use Rebing\GraphQL\Support\InterfaceType;
use GraphQL\Type\Definition\Type;

class SluggableInterface extends InterfaceType
{


    protected $attributes = [
        'name' => 'Sluggable',
        'description' => 'An type that can be uniquely identified by a string that is safe to use in a url.'
    ];


    /** @inheritdoc */
    public function fields()
    {
        return [
            'slug' => [
                'type' => Type::string(),
                'description' => 'The url-safe string that uniquely identifies this object.'
            ]
        ];
    }

    /** @inheritdoc */
    public function resolveType($root) {
        if($root instanceof CertificateCategory) {
            return GraphQL::type('CertificateCategory');
        } elseif ($root instanceof GroupCategory) {
            return GraphQL::type('GroupCategory');
        } elseif ($root instanceof Group) {
            return GraphQL::type('Group');
        }
    }

}