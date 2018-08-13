<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 23:22
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Enums;


use Rebing\GraphQL\Support\Type;
use Roelhem\RbacGraph\Enums\NodeType;

class RbacNodeTypeEnum extends Type
{

    protected $enumObject = true;

    public function attributes()
    {

        $values = [];
        foreach(NodeType::getEnumerators() as $enumerator) {
            $values[$enumerator->getName()] = $enumerator;
        }

        return [
            'name' => 'RbacNodeType',
            'description' => 'An enum with the different types of nodes.',
            'values' => $values
        ];
    }

}