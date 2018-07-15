<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 04:29
 */

namespace App\GraphQL\Enums;


use App\Enums\MembershipStatus;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MembershipStatusEnum extends GraphQLType
{

    protected $enumObject = true;

    public function attributes()
    {
        return [
            'name' => 'MembershipStatus',
            'description' => 'A status of a membership.',
            'values' => $this->getValues()
        ];
    }

    protected function getValues()
    {
        $res = [];
        foreach (MembershipStatus::getEnumerators() as $enumerator) {
            $res[$enumerator->getName()] = $enumerator;
        }
        return $res;
    }

}