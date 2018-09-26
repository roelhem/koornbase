<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 07:24
 */

namespace Roelhem\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType as BaseObjectType;
use Roelhem\GraphQL\Types\Traits\HasFieldsMethod;

abstract class ObjectType extends BaseObjectType
{

    use HasFieldsMethod;



    public function __construct(array $config = [])
    {
        parent::__construct(array_merge([
            'interfaces' => [$this,'interfaces'],
            'description' => $this->description,
        ],$config));
    }

    /**
     * An array of all the interfaces that this object implements.
     *
     * @return array
     */
    protected function interfaces()
    {
        return [];
    }

}