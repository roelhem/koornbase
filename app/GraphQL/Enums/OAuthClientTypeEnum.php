<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 04:29
 */

namespace App\GraphQL\Enums;


use App\Enums\OAuthClientType;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OAuthClientTypeEnum extends GraphQLType
{

    protected $enumObject = true;

    public function attributes()
    {
        return [
            'name' => 'OAuthClientType',
            'description' => 'The different types of OAuth clients, each with a different method of getting access tokens.',
            'values' => $this->getValues()
        ];
    }

    protected function getValues()
    {
        $res = [];
        foreach (OAuthClientType::getEnumerators() as $enumerator) {
            $res[$enumerator->getName()] = $enumerator;
        }
        return $res;
    }

}