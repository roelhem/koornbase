<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 07:35
 */

namespace App\GraphQL\Enums;


use App\Enums\OAuthProvider;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OAuthProviderEnum extends GraphQLType
{

    protected $enumObject = true;

    public function attributes()
    {
        return [
            'name' => 'OAuthProvider',
            'description' => 'A OAuth-server provider.',
            'values' => $this->getValues()
        ];
    }

    protected function getValues()
    {
        $res = [];
        foreach (OAuthProvider::getEnumerators() as $enumerator) {
            $res[$enumerator->getName()] = $enumerator;
        }
        return $res;
    }

}