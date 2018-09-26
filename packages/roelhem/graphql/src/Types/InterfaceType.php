<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 07:55
 */

namespace Roelhem\GraphQL\Types;

use GraphQL\Type\Definition\InterfaceType as BaseInterfaceType;
use Roelhem\GraphQL\Types\Traits\HasFieldsMethod;

abstract class InterfaceType extends BaseInterfaceType
{
    use HasFieldsMethod;

    public function __construct(array $config = [])
    {

        if(!isset($config['description'])) {
            $config['description'] = $this->description;
        }

        parent::__construct($config);
    }

}